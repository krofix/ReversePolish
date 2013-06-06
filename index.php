<!doctype html>
<meta charset="utf8">
<title>Обратная польская нотация</title>

<?php

include 'lib/Log.php';
include 'lib/ReversePolish.php';
include 'lib/AnonymousFunctionsRPO.php';
include 'lib/StringRPI.php';

/* Main
---------------------------------------- */
// Создается объект управления операторами (ОУО) # AnonymousFunctionsRPO::__constructor()
$operatorController = new AnonymousFunctionsRPO;
// ОУО создает новый оператор + # AnonymousFunctionsRPO::addOperator()
$operatorController->addOperator( '+', function ( $a, $b ) { return $a + $b; } );
// Создание нового объекта интерфейса (передается ОУО) # StringRPI::__constructor()
$notation = new StringRPI( $operatorController );
// Создаем новый оператор * через интерфейс стэка # ReversePolish::addOperator()
$notation->addOperator( '*', function ( $a, $b ) { return $a * $b; } );
// Удаляем оператор + # AnonymousFunctionsRPO::removeOperator (ОУО был склонирован поэтому удаление не повлияет на работу объекта стэка)
$operatorController->removeOperator( '+' );
// Запускается команда и выводится ее результат # StringRPI::execute()
$equation = '1 2 + 4 * 3 +';
$result = $notation->execute( $equation );

echo "<p>Результатом выполнения уравнения '<em>{$equation}</em>' является число <em>{$result}</em>.</p>"

?>