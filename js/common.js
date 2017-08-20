$(document).ready(function() {	
	$('#datetimepicker1').datetimepicker({
        format: 'yyyy-mm-dd',
        minView: 'month',
        weekStart: 1,
        autoclose: true
    }).on('changeDate', function(ev){
        updateUI(ev.date)
    });

    $("#photo").change(function(){
        readURL(this);
    });

    $('#delete').hide();

    $('#delete').on('click', function(){
        if(!confirm('Är du säker på att du vill ta bort detta event?'))
            return false;
    });

    $('.add_user_to_display').on('click', showAddUserDisplay);

    $('.selectpicker').on('change', function() {
        var display_ids = $('#display').val(); 

        if(display_ids.length == 1) {
            $('#show_screen').show();
            $('#show_screen').attr('href', '?controller=display&action=show&id='+display_ids[0])
        } else {
            $('#show_screen').hide();
        } 
        
        var date = $('#date').val();
        if(date == '') {
            return;
        }

        updateUI(date);
    });

    $('.selectpicker').selectpicker({
        noneSelectedText: 'No display selected'
    });
      
});

function updateUI(date) 
{
    var d = new Date(date);
    var day = $.fn.datetimepicker.dates['en'].days[d.getDay()];
    
    $('#standard').text('Använd som standard för alla ' + day.toLowerCase() + 'ar');

    var display_ids = $('#display').val(); 

    if($('#display').val() == '') {
        $('#display').focus();
        return;
    }

    if(display_ids.length > 1) {
        setInputs();
        $('#header').text('Display settings');
        return;
    }

    var display_id = display_ids[0];

    overlay_on();

    // Fetch events for this day!
    $.ajax({
        url: '?controller=home&action=getEvent',
        data: {
            date: $('#date').val(),
            display_id: display_id
        },
        dataType: 'json'
    })
    .done(function(response) {
        if(!response.length) {
            setInputs();
            $('#header').text('Display settings');
            overlay_off();
            return;
        }

        var event = response[0];
        if(response.length > 1) {
            // This means we have a standard event and a special event.
            for(var i = 0; i < response.length; i++) {
                if(!response[i].standard) {
                    event = response[i];
                    break;
                }
            }
        }

        setInputs(event.id, event.title, event.description, event.photo_id, event.filename, !event.standard);
    
        overlay_off();
    });
}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photo-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function setInputs(event_id = '', title = '', description = '', photo_id = '', photoURL = '#', enableDelete = false)
{
    $('#event_id').val(event_id);
    $('#title').val(title);
    $('#description').val(description);
    $('#photo_id').val(photo_id);
    $('#photo-preview').attr('src', photoURL);

    if(enableDelete) {
        $('#header').text('Display settings - Special event');
        $('#delete').attr('href', '?controller=home&action=deleteEvent&event_id='+event_id);
        $('#delete').show();
    } else {
        $('#header').text('Display settings - Standard event');
        $('#delete').attr('href', '');
        $('#delete').hide();
    }
}

function overlay_on() {
    $('#overlay').fadeIn(200);
}

function overlay_off() {
    $('#overlay').fadeOut(200);
}


function showAddUserDisplay()
{
    var display_id = $(this).attr('data-display-id');

    $('#modal-title').html('Add a user to display "' + $(this).attr('data-display-name') + '"');
    $('#display_id').val(display_id);
    
    $('.user-option').each(function() {
        $(this).show();
        $(this).prop("selected", true);
        if($(this).attr('data-display') === undefined) {
            return;
        }

        var displays = $(this).attr('data-display').split(',');
        
        if(displays === undefined || displays.length <= 0) {
            return;
        }
        
        for(var i = 0; i < displays.length; ++i) {
            if(displays[i] == display_id) {
                $(this).hide();
                $(this).prop("selected", false);
                break;
            }
        }
    });

    $('#addUserDisplay').modal('show');
}