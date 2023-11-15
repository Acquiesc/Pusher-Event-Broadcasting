
function setOnline(user_id) {
    fetch('/online', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'user_id': user_id,
            'online': false,
        })
    }).then(function(res) {
        //document.getElementById('logout_form').submit()                            
        
        console.log(res)

        //return res.json()
    })
//.then(function(data) {
//    console.log(`sent: ${data}`)
// })
}

