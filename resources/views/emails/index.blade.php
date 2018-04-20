<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container">
    @if(!empty($alert['message']))
        <div class="alert alert-{{ $alert['type'] }}" role="alert">
            @lang($alert['message'])
        </div>
    @endif


    <form  action="{{ action('EmailController@create') }}" method="post">
        @csrf

        <div class="form-group">
            <input type="text" class="form-control" name="{{ \App\Models\Member::EMAIL }}">
        </div>

        <input type="hidden" name="id" value="">

        <button class="btn btn-primary">@lang('emails.index.send')</button>
    </form>

    <ul class="list-group">
        @if(count($emails) > 0)
            @foreach($emails as $email)
                <li id="id-{{ $email['id'] }}" class="list-group-item"><span>{{ $task[\App\Models\Member::EMAIL] }}</span> <a class="btn btn-primary update" href="">@lang('emails.index.modify')</a>
                    <a class="btn btn-danger delete" href="" data-toggle="modal" data-target="#confirm">@lang('emails.index.delete')</a></li>
            @endforeach
        @else
            <li class="list-group-item">@lang('emails.index.task_not_found')</li>
        @endif
    </ul>
</div>


<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @lang('emails.index.are_you_sure')
            </div>
            <div class="modal-footer">
                <button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">@lang('cancel')</button>
                <a id="ok" href="" class="btn btn-primary">@lang('ok')</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="application/javascript">
    $(document).ready(function() {
        var url = '{{ action('EmailController@update') }}'

        function getId(that) {
            var id = that.parent().attr('id')
            return id.split('-')[1]
        }

        $('li .update').on('click', function(e) {
            e.preventDefault();

            var id = getId($(this))

            $('input[email=id]').val(id)
            $('input[type=text]').val($(this).parent().find('span').text())
            $('form').attr('action', url)
        });

        $('li .delete').on('click', function(e) {
            e.preventDefault()

            var id = getId($(this))

            $('.modal #ok').attr('href', '/emails/delete/' + id)
        })
    })
</script>
</body>
</html>
