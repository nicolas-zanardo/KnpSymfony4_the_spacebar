const likeArticle = document.querySelector('.js-like-article');

getAjax = function(url, methodVerb, selectElt, urlData) {
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
            selectElt.innerHTML = jsonResponse[urlData];
        }
    };

    xhr.open(methodVerb, url, true);
    xhr.timeout = 60000;
    xhr.send(null);
};

likeArticle.addEventListener('click', function (e) {
    e.preventDefault();
    let link = e.currentTarget;
    link.classList.toggle('fa-heart-o');
    link.classList.toggle('fa-heart');

    const jsLikeArticleCount = document.querySelector('.js-like-article-count');
    const url = "http://localhost:8000/news/why-do-ateroids-taste-like-bacon/heart";

    // AJAX
    getAjax(url, "POST", jsLikeArticleCount, "hearts");

})

// $(document).ready(function () {
//    $('.js-like-article').on('click', function (e) {
//        e.preventDefault();
//
//        let $link = $(e.currentTarget);
//        $link.toggleClass('fa-heart-o').toggleClass('fa-heart');
//
//        $.ajax({
//            method: 'POST',
//            url: $link.attr('href'),
//        }).done(function (data) {
//            $('.js-like-article-count').html(data.hearts);
//        });
//
//    });
// });