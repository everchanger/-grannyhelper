$(document).ready(function() {	
    setInterval(needsUpdate, 600000);
});

function needsUpdate()
{
    // Fetch events for this day!
    $.ajax({
        url: '?controller=home&action=needsRefresh',
        data: {
            event_hash: $('#event_hash').val(),
            id: $('#display_id').val()
        },
    })
    .done(function(response) {
        if(!response.length) {
            return;
        }
        location.reload();
    });
}