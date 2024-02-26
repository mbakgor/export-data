function requestDevices(data) {
    var formData = new FormData(data);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/plugins/export-data");
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

    xhr.onload = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('result_devices').innerHTML = xhr.responseText;
        } else {
            console.log('error');
        }

        
    };

    xhr.send(formData);
}

    function getDevices(data) {
        var formData = new FormData(data);
    
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/plugins/export-data/get-devices");
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    
        xhr.onload = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('select_devices').innerHTML = xhr.responseText;
            } else {
                console.log('error');
            }
        };

        xhr.send(formData);
}

