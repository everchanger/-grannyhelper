$(document).ready(function() {	
	$('#datetimepicker1').datetimepicker({
        format: 'yyyy-mm-dd',
        minView: 'month',
        weekStart: 1,
        autoclose: true
    }).on('changeDate', function(ev){
        var d = new Date(ev.date);
        var day = $.fn.datetimepicker.dates['en'].days[d.getDay()];
        
        $('#standard').text('Använd som standard för alla ' + day.toLowerCase() + 'ar');

        overlay_on();

        // Fetch events for this day!
        $.ajax({
            url: '?controller=home&action=getEvent',
            data: {date: $('#date').val()},
            dataType: 'json'
        })
        .done(function(response) {
            if(!response.length) {
                setInputs();
                $('#header').text('Inställningar');
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
    });

    $("#photo").change(function(){
        readURL(this);
    });

    $('#delete').hide();

    $('#delete').on('click', function(){
        if(!confirm('Är du säker på att du vill ta bort detta event?'))
            return false;
    });
});

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
        $('#header').text('Inställningar - Särskild händelse');
        $('#delete').attr('href', '?controller=home&action=deleteEvent&event_id='+event_id);
        $('#delete').show();
    } else {
        $('#header').text('Inställningar - Standard händelse');
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
