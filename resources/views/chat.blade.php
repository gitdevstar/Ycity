<div class="container-fluid p-0">
    <div id="chat">
        <div class="row m-0">
            <div class="col-10 p-0">
                <chat-messages
                    :job="{{ $job->id }}"
                    :user="{{ Auth::id() }}"
                    :messages="messages">
                </chat-messages>
                @if($job->status_fk == 3)
                    <p class="mt-3">Der Chat wurde archiviert, da der Auftrag abgeschlossen ist. Es können keine weitere Nachrichten gesendet werden.</p>
                @else
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user_id="{{ Auth::id() }}"
                        :job="{{ $job->id }}"
                        :image="'{{ Auth::user()->image}}'"
                        :firstname="'{{ Auth::user()->firstname}}'"
                        :time="'{{ date('d. M y H:i') }}'">
                    </chat-form>

                    @if(Session::get('userType') == 'creator')
                        <p class="mt-3">Dieser Auftrag ist von <a target="_blank" href="{{$job->client_website}}" class="link-underline">{{$job->client_name}}</a>, du chattest nun mit der zuständigen Kontaktperson.</p>
                    @else
                        <p class="mt-3">Dieser Auftrag ist in Bearbeitung vom Creator <a class="link-underline" href="/ycity/creator/{{$job->creators_fk}}/{{urlencode($job->firstname)}}-{{urlencode($job->lastname)}}">{{$job->firstname}} {{$job->lastname}}</a>, du kannst nun mit ihm chatten.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
