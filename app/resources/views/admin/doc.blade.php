@extends('layouts.admin')

@yield('content')

<div class="container pt-5">
    <div id="accordion" class="mt-5">
        @php ($i = 1)
        @foreach($data as $key => $group)
            <div class="card">

                <div class="card-header" id="headingOne{{$i++}}">
                    <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{$i}}" aria-expanded="true" aria-controls="collapseOne{{$i}}">
                        {{$group[0]->title}}
                    </button>
                    </h5>
                </div>

                <div id="collapseOne{{$i}}" class="collapse" aria-labelledby="headingOne{{$i}}" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table">
                            <tr class="thead-dark">
                                <th>Method</th>
                                <th style="width: 30%">URL</th>
                                <th>Description</th>
                            </tr>
                            @foreach($group as $item)
                            <tr>
                                <td><b class="text-danger">[{{$item->method}}]</b></td>
                                <td>
                                    @php($item->url = str_replace('{','<b class="text-primary">',$item->url))
                                    @php($item->url = str_replace('}','</b>',$item->url))
                                    {!!$item->url!!}
                                </td>
                                <td>{!!$item->description!!}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

