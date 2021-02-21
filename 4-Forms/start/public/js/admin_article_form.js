const locationSelect = document.querySelector('.js-article-form-location');
const specificLocationTarget  = document.querySelector('.js-specific-location-target');


locationSelect.addEventListener('change', () => {
    let xhr;
    if(window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject){
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert('your browser does not support Ajax');
    }

    // let location = "";
    // if (locationSelect.value) {
    //
    // }
    let location = "?location=" + locationSelect.value

    xhr.open('GET', locationSelect.dataset.specificLocationUrl + location, true);
    xhr.timeout = 60000;
    xhr.send(null);

    xhr.onreadystatechange = function (e){
        if (xhr.readyState === 4 && xhr.status === 204) {
            if (specificLocationTarget.querySelector('select')) {
                specificLocationTarget.querySelector('select').remove();
            }
            specificLocationTarget.classList.add('d-none');
        }
        if (xhr.readyState === 4 && xhr.status === 200) {
            specificLocationTarget.innerHTML = this.response;
            specificLocationTarget.classList.remove('d-none');
        }

    }

})