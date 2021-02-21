const searchInput = document.querySelector('.js-user-autocomplete')
const autocompleteUrl = searchInput.dataset.autocompleteUrl



autocomplete('.js-user-autocomplete', { hint: false }, [
    {
        source: (query, cb) => {
            let xhr;

            if(window.XMLHttpRequest){
                xhr = new XMLHttpRequest();
            } else if (window.ActiveXObject){
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            } else {
                alert('your browser does not support Ajax');
            }
            xhr.onreadystatechange = function () {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    let jsonResponse = JSON.parse(this.responseText);
                    cb(jsonResponse.user)
                }
            };

            xhr.open('GET', autocompleteUrl + '?query=' + query, true);
            xhr.timeout = 60000;
            xhr.send(null);
            },
        displayKey: 'email',
        debounce: 500
    }]);


