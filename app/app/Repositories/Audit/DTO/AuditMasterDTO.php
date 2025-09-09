<?php

namespace App\Repositories\Audit\DTO;

Class AuditMasterDTO
{
    private $result = [
        'positive' => [],
        'negative' => [],
        'neutral' => [],
    ];
    private $trafic_id;
    private $audit_id;



    public function __construct(array $data)
    {
        $this->result['positive'] = isset($data['result']['positive']) ? $data['result']['positive'] : [];
        $this->result['negative'] = isset($data['result']['negative']) ? $data['result']['negative'] : [];
        $this->result['neutral'] = isset($data['result']['neutral']) ? $data['result']['neutral'] : [];
        $this->trafic_id = $data['trafic_id'];
        $this->audit_id = $data['audit_id']; 
    }



    public function getAll()
    {
        return [
            'result' => $this->result,
            'trafic_id' => $this->trafic_id,
            'audit_id' => $this->audit_id
        ];
    }
}