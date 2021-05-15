$(function(){
    
    console.log('Привет, это стaрый js ))');
    reqAjaxGet();
    reqAjaxPost();
});

function reqAjaxGet() 
{
    $('a.ajaxArticleBodyByGetNew').one('click', function(){
        var contentId = $(this).attr('data-summury');
        console.log('ID статьи = ', contentId); 
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showSummaryHandler.php?articleId=' + contentId, 
            dataType: 'json'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен');
            $('li.' + contentId).append(obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
    
            console.log('Ошибка соединения при получении данных (GET)');
        });
        
        return false;
        
    });  
}

function reqAjaxPost() 
{
    $('a.ajaxArticleBodyByPostNew').one('click', function(){
        var content = $(this).attr('data-summury');
//        console.log('ID статьи = ', content);
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showSummaryHandler.php',
            data: 'articleId=' + content ,
            dataType: 'json',
//            converters: 'json text',
            method: 'POST'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен', obj);
            $('li.' + content).append(obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
    
            console.log('Ошибка соединения с сервером (POST)');
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
        });
        
        return false;
        
    });  
}
