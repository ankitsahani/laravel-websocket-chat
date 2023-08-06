
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//save chats
$('#chat-form').submit(function (e) {
    e.preventDefault();

    let message = $("#message").val();

    $.ajax({
        url: $('#chat-form').attr('action'),
        type: $('#chat-form').attr('method'),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
        data: {
            sendorId: sendorId,
            recieverId: recieverId,
            message: message
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $("#message").val('');
                let chat = res.data.message;
                let chatMeg = `
                <li class="clearfix" id="${res.data.id}-chat">
                   <div class="message my-message float-right">
                      <span>${chat}</span> 
                      <i class="fa fa-edit" data-id="${res.data.id}" data-msg="${res.data.message}" area-hidden="true" data-target="#updateModal" data-toggle="modal"></i>
                      <i class="fa fa-trash" style="color:red" data-id="${res.data.id}"  area-hidden="true" data-target="#deleteModal" data-toggle="modal"></i>
                   </div>
                </li>
                `;
                $(".chat-container").append(chatMeg);
                scrollChat();
            } else {
                alert(res.msg);
            }
        }
    })
})

$(document).ready(function () {
    $('.show-selection').show();
    $('.user-section').hide();
    $('.user-list').click(function () {
        var getUserId = $(this).attr('data-id');
        recieverId = getUserId;
        $('.user-section').show();
        $('.show-selection').hide();
        loadOldChats();
    });
});

Echo.join('user-status')
    .here((users) => {
        for (let x = 0; x < users.lenght; x++) {
            if (sendorId != users[x]['id']) {
                $("#" + users[x]['id'] + "-status").removeClass('offline');
                $("#" + users[x]['id'] + "-status").addClass('online');
            }
        }
    })
    .joining((user) => {
        $("#" + user.id + "-status").removeClass('offline');
        $("#" + user.id + "-status").addClass('online');

    })
    .leaving((user) => {
        $("#" + user.id + "-status").removeClass('online');
        $("#" + user.id + "-status").addClass('offline');
    })
    .listen('UserStatus', (e) => {
        console.log(e);
    });

Echo.private('boradcast-message')
    .listen('.getChatMessage', (data) => {

        if ((sendorId == data.chat.reciever_id) && (recieverId == data.chat.sender_id)) {
            let chat = data.chat.message;
            let chatMeg = `
            <li class="clearfix">
              <div class="message other-message" id="${data.chat.id}-chat">
                 <span>${chat}</span>
                 <i class="fa fa-edit" data-id="${data.chat.id}" data-msg="${data.chat.message}" area-hidden="true" data-target="#updateModal" data-toggle="modal"></i>
                 <i class="fa fa-trash" style="color:red" data-id="${data.chat.id}"  area-hidden="true" data-target="#deleteModal" data-toggle="modal"></i>
              </div>
            </li>
            `;
            $(".chat-container").append(chatMeg);
            scrollChat();
        }

    });

//delete chat message 
Echo.private('message-deleted')
    .listen('MessageDeleteEvent', (data) => {
        $(`#${data.id}-chat`).remove();
    })

//load old chats
function loadOldChats() {
    $.ajax({
        url: '/load-chat',
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
        data: {
            sendorId: sendorId,
            recieverId: recieverId
        },
        dataType: 'json',
        success: function (res) {

            if (res.success) {
                let chatss = res.data;

                let html = '';
                for (let i = 0; i < Object.keys(chatss).length; i++) {

                    let addClass = '';
                    if (chatss[i].sender_id == sendorId) {
                        addClass = "my-message float-right";
                    } else {
                        addClass = "other-message";
                    }
                    html += `
                    <li class="clearfix">
                        <div class="message ${addClass}" id="${chatss[i].id}-chat">
                          <span>${chatss[i].message}</span>`;
                    if (chatss[i].sender_id == sendorId) {
                        html += `
                        <i class="fa fa-edit"   data-id="${chatss[i].id}" data-msg="${chatss[i].message}" area-hidden="true" data-target="#updateModal" data-toggle="modal"></i>
                        <i class="fa fa-trash"  style="color:red" data-id="${chatss[i].id}" area-hidden="true" data-target="#deleteModal" data-toggle="modal"></i>`;
                    }
                    html += `
                        </div>
                    </li>
                    `;
                }
                $(".chat-container").append(html);
                scrollChat();
            } else {
                alert(res.msg);
            }
        }
    })
}

//scroll chat

function scrollChat() {
    $(".chat-history").animate({
        scrollTop: $(".chat-history").offset().top + $(".chat-history")[0].scrollHeight
    }, 0);
}

//delete chats
$(document).on('click', '.fa-trash', function () {
    let id = $(this).attr('data-id');
    $("#delete-chat-id").val(id);
    $("#delete-message").text($(this).parent().text());
});

$('#delete-chat-form').submit(function (e) {
    e.preventDefault();
    let id = $("#delete-chat-id").val();
    $.ajax({
        url: $('#delete-chat-form').attr('action'),
        type: $('#delete-chat-form').attr('method'),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
        data: {
            id: id
        },
        dataType: 'json',
        success: function (res) {
            alert(res.msg);
            if (res.success) {
                $(`#${id}-chat`).remove();
                $("#deleteModal").modal('hide');
            }
        }
    })
});

//update chat 
$(document).on('click', '.fa-edit', function () {
    $('#update-chat-id').val($(this).attr('data-id'));
    $('#update-message').val($(this).attr('data-msg'));
});

$(document).ready(function () {
    $('#update-chat-form').submit(function (e) {
        e.preventDefault();
        let id = $("#update-chat-id").val();
        let message = $("#update-message").val();
        $.ajax({
            url: $('#update-chat-form').attr('action'),
            type: $('#update-chat-form').attr('method'),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
            data: {
                id: id,
                message: message
            },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $("#updateModal").modal('hide');
                    $(`#${id}-chat`).find('span').text(message);
                    $(`#${id}-chat`).find('.fa-edit').attr('data-msg', message);
                } else {
                    alert(res.msg);
                }
            }
        })
    });
});

//update websocket
Echo.private('message-updated')
    .listen('MessageUpdateEvent', (data) => {
        console.log(data,'updatechat');
        $(`#${data.id}-chat`).find('span').text(data.message);
        // $(`#${data.id}-chat`).find('.fa-edit').attr('data-msg', data.message);
    });