<?php

namespace App\Models\Traits;

use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;

trait CarPaginatable
{
    public function scopeWithDataForCarList(Builder $builder)
    {
        if($this::class === Car::class)
        $builder->with([
            'priority.sale_priority',
            'brand' => function($q) {
                $q->select('id', 'name');
            },
            'mark' => function($q) {
                $q->select('id', 'name');
            },
            'color' => function($q) {
                $q->select('id','name','base_id')->with([
                    'base' => function($qb){
                        $qb->select('id','web');
                    },
                ],);
            },
            'order' => function($q){
                $q->select('order_number', 'car_id');
            },
            'provider' => function($q) {
                $q->select('car_id', 'provider_id')
                    ->with(['provider' => function($qp){
                        $qp->select('id', 'firstname', 'lastname','fathername', 'company_name');
                    }]);
            },
            'marker'  => function($q){
                $q->select('car_id', 'marker_id')
                    ->with(['marker' => function($qtm){
                        $qtm->select('id', 'name','text_color','body_color','description');
                    }]);
            },
            'trade_marker' => function($q){
                $q->select('car_id', 'trade_marker_id')
                    ->with(['marker' => function($qtm){
                        $qtm->select('id', 'name','text_color','body_color','description');
                    }]);
            },
            'order_type' => function($q) {
                $q->select('car_id', 'order_type_id')
                    ->with(['type' => function($qot){
                        $qot->select('id', 'name', 'text_color');
                    }]);
            },
            'purchase' => function($q){
                $q->select('car_id', 'cost');
            },
            'delivery_terms' => function($q){
                $q->select('car_id','delivery_term_id')
                    ->with(['term' => function($qterm){
                        $qterm->select('id','name','text_color');
                    }]);
            },
            'detailing_costs',
            'tuning_price',
            'gift_price',
            'over_price',
            'state_status',
            'complectation' => function ($builderComplectation) {
                    $builderComplectation->select('id','name','code','motor_id','body_work_id','vehicle_type_id')
                    ->with(['motor' => function ($builderMotor) {
                        $builderMotor->select('id','power','size','motor_driver_id','motor_transmission_id')
                        ->with([
                            'transmission' => function($qtrans){
                                $qtrans->select('id','acronym');
                            }, 
                            'driver' => function($qdrive){
                                $qdrive->select('id','acronym');
                            }
                        ]);
                    },
                    'file' => function($q){
                        $q->select('file','complectation_id');
                    },
                    'vehicle' => function($q){
                        $q->select('name', 'id');
                    },
                    'bodywork' => function($q){
                        $q->select('name','id');
                    },
                    'prices',
                    'current_price',
                ]);
            },

            'collector' => function($q){
                $q->select('car_id','collector_id')
                    ->with(['collector' => function($qcollector){
                        $qcollector->select('id', 'name');
                    }]);
            },

            'logistic_dates' => function($q){
                $q->select('car_id', 'logistic_system_name', 'date_at', 'author_id')
                    ->with('author');
            },

            'reserve' => function ($builderReserve) {
                $builderReserve->select('id','worksheet_id','car_id','created_at', 'author_id')
                    ->with([
                        'lisinger',
                        'author',
                        'discounts',
                        'sale',
                        'issue',
                        'contract' => function($qc){
                            $qc->select('id','reserve_id','dkp_offer_at','dkp_closed_at')
                                ->with([
                                    'option_price',
                                    'complectation_price'
                                ]);
                        }, 
                        'worksheet' => function($qw){
                            $qw->select('id', 'client_id');
                        }
                    ]);
            },
            'owner' => function($q){
                $q->select('car_id', 'client_id');
            },
            'options' => function($q){
                $q->with(['current_price','prices']);
            },

            'car_status_type'
        ]);
    }
}