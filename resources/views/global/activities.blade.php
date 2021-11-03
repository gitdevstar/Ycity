<h2 class=" pb-3">{{__('main.activity')}}</h2>
<div id="dashboardActivities">
    @if(count($activities) == 0)
        <img id="no_dashboard_activities" class="w-100 my-2 mx-auto d-table" style="max-width:256px; height: auto; " src="/images/icons/activities-empty-state.png" alt="{{__('main.no_activities')}}" />
    @else
        @foreach($activities as $activity)
            <div class="row row-flex m-0">
                <div class="col col-2 col-md-1 pl-0 pr-0 pt-0 w-100 overflow-hidden">
                    <a href="{{$activity->link}}"><img src="/images/icons/activity/{{$activity->type}}.svg" /></a>
                    <div class="activityLine"></div>
                </div>
                <div class="col col-8 col-sm-8 col-md-9 p-0 w-100 ">
                    <h3>{{$activity->title}}</h3>
                    @if($activity->link !== null)
                        <p><a href="{{$activity->link}}">{{$activity->message}}</a></p>
                    @else
                        <p>{{$activity->message}}</p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
