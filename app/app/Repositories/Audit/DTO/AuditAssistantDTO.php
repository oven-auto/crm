<?php

namespace App\Repositories\Audit\DTO;

Class AuditAssistantDTO
{
    private int $trafic_id;
    private int $audit_id;
    private string $result;



    public function __construct(array $data)
    {
        $this->trafic_id = $data['trafic_id'];
        $this->audit_id = $data['audit_id'];
        $this->result = $data['result']?? [];
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