<?php

namespace AwsInspector\Model\Route53;

use AwsInspector\SdkFactory;

class Repository {

    protected $recordSets = [];

    /**
     * Repository constructor.
     *
     * @param $hostedZoneId
     */
    public function __construct($hostedZoneId)
    {
        $r53Client = SdkFactory::getClient('Route53', 'default', ['region' => 'us-east-1']); /* @var $r53Client \Aws\Route53\Route53Client */
        $res = $r53Client->listResourceRecordSets([
            'HostedZoneId' => $hostedZoneId
        ]);
        foreach ($res->search('ResourceRecordSets') as $recordSet) {
            $name = $recordSet['Name'];
            $type = $recordSet['Type'];
            unset($recordSet['Name']);
            unset($recordSet['Type']);
            $this->recordSets[$name][$type] = $recordSet;
        }
    }

    public function findByRecordSet($regex)
    {
        foreach ($this->recordSets as $name => $data) {
            if (preg_match($regex, $name)) {
                return $data;
            }
        }
        return [];
    }

    public function findByRecordSetNameAndType($recordSetName, $type)
    {
        $recordSetsForName = $this->findByRecordSet($recordSetName);
        if (count($recordSetName) == 0) {
            return [];
        } elseif (!isset($recordSetsForName[$type])) {
            return [];
        } else {
            return $recordSetsForName[$type];
        }
    }

}