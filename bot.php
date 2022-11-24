<?php
// создаем переменную, куда будем получать запросы от бота
$get_input = file_get_contents('php://input');
const token = '5940823121:AAE7I3k2TNUFGqds5bbab9uyHpZY-3Kbv1Q';
// ну здесь все понятно, ссылка взята из документации + сам токен и + метод 
const api_url = 'https://api.telegram.org/bot';
// Введем функцию для распечатывания ответа от сервера
function printAnswer($str)
{
   echo "<pre>";
   print_r($str);
   echo "</pre>";
}
// Введем основную функцию для общения с сервером телеграм
// в $options мы можем, к примеру, передать id чата, откуда делается запрос
function getTelegramApi($method, $options = null)
{

   // Создаем переменную запроса, склеивая 3 компонента 
   $str_request = api_url . token . '/' . $method;

   // При наличии $options типа добавляем эти опции к строке запроса
   if ($options) {
      // http_build_query($options) создает урл из массива, т.е. $options должны быть массивом
      $str_request .= '?' . http_build_query($options);
   }
   // Делаем запрос к telegram-bot -api
   $request = file_get_contents($str_request);
   // преобразуем полученный ответ 
   return json_decode($request, 1);
}

// Создадим функцию для установки и снятия хуков при помощи хука мы даем телеграм апи
// адрес сервера с нашим ботом и даем тем самым понять, что по данному адресу находится сервер,
// с которого управляется бот
function setHook($set = 1)
{
   // в этой переменной находится путь к нашему серверу с кодом бота, если set=1, что по умолчанию,
   // то вебхук устанавливается, если 0, то снимается, так как через тернарный оператор передается пустая строка
   $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

   printAnswer(
      getTelegramApi(
         // setWebhook это походу метод и взят из документации
         'setWebhook',
         [
            'url' => $set ? $url : ''
         ]
      )
   );
   exit();
}
// установка хука
setHook(1);
