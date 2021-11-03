@extends('layouts.app')

@section('headTitle')
    {{ __('job.jobs') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
<div class="container-fluid">
    <p class="h5 pb-3">{{ __('job.jobs') }}</p>
    <div id="app2" v-cloak>
    <job-component inline-template>
        <div>
            <div class="alert alert-danger" v-if="contactAdmin">
                Es ist ein Fehler vorgetreten. Bitte kontaktieren Sie den Admin.
            </div>
            <div class="alert-success alert" v-if="success">
                Auftrag erfolgreich gelöscht.
            </div>
            <table class="table">
                <tr>
                    <th>Titel</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @forelse($jobs as $job)
                    <tr id="{{$job->id}}">
                        <td><a class="btn-link" href="/ycity/client/job/{{$job->id}}">{{$job->name}}</a></td>
                        <td>{{$job->status}}</td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm float-right ml-2 p-2 deleteButton" data-name="{{$job->name}}" data-id="{{$job->id}}" data-toggle="modal" data-target="#deleteConfirmModal" type="button"><i class="fa float-right fa-trash"></i></button>
                            <a class="btn btn-outline-primary btn-sm float-right" href="/ycity/client/job/{{$job->id}}/edit"><i class="fa fa-edit mr-2"></i>{{ __('job.edit_job') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2"><p>Keine Einträge vorhanden</p></td>
                    </tr>
                @endforelse
            </table>
        </div>
    </job-component>
    </div>
    <a class="btn btn-primary btn-sm" href="job/create"><i class="fa fa-plus-circle mr-2"></i>{{ __('job.create_new_job') }}</a>
</div>
@endsection

