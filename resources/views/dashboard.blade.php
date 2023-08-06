<x-app-layout>
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search..." />
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach ($users as $user)
                                <li class="clearfix user-list" data-id="{{ $user->id }}">
                                    <img src="{{ asset('storage/users/' . $user->image) }}" alt="avatar" />
                                    <div class="about">
                                        <div class="name">{{ $user->name }}</div>
                                        <div class="status">
                                            <i id="{{ $user->id }}-status" class="fa fa-circle update-status"></i>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="chat user-section">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar" />
                                    </a>
                                    <div class="chat-about">
                                        <h6 class="m-b-0">Aiden Chavez</h6>
                                        <small>Last seen: 2 hours ago</small>
                                    </div>
                                </div>
                                <div class="col-lg-6 hidden-sm text-right">
                                    <a href="javascript:void(0);" class="btn btn-outline-secondary"><i
                                            class="fa fa-camera"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary"><i
                                            class="fa fa-image"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-info"><i
                                            class="fa fa-cogs"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-warning"><i
                                            class="fa fa-question"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="chat-history" style="overflow-y: scroll; height:380px;">
                            <ul class="m-b-0 chat-container">
                                <div class="otherMessage"></div>
                                <div class="myMessage"></div>
                            </ul>
                        </div>
                        <div class="chat-message clearfix">
                            <form action="{{ route('save_chat') }}" id="chat-form" method="POST">
                                @csrf
                                <div class="input-group mb-0">
                                    <input type="text" name="message" id="message" class="form-control"
                                        placeholder="Enter text here..." required />
                                    <div class="input-group-prepend">
                                        <button type="submit">
                                            <span class="input-group-text"><i class="fa fa-send"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="chat show-selection" style="width: 100%; height:400px;">
                        <div class="conatiner-flued">
                            <h1 class="text-center pt-5">Select User To Chat</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Modal -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete_chat') }}" id="delete-chat-form" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                    <b>
                        <p id="delete-message"></p>
                    </b>
                    <input type="hidden" name="id" id="delete-chat-id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">No</button>
                    <button type="submit" class="btn btn-danger">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
