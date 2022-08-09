<?php

namespace common\models\interface;

interface RelationInterface
{
    /**добавление или обновление записи*/
    public function addOrUpdateRelation():void;

    /**получает существующую модель. данные для изменения должны быть у самой связи*/
    public function updateRelation(self $relation):void;

    /**Модель должна содержать поля для поиска - родительский и дочерний id*/
    public function findRelation():self|null;

    /**Удаляем одну связь по первичному ключу*/
    public function deleteByPrimary(int $id):void;

    /**Удаляем по комбинации родительского и дочернего поля*/
    public function deleteByFields(int $parent_id, int $child_id):void;

    /**Убираем элемент родительской колонки таблицы связи*/
    public function removeFromParentRelation(int $id):void;

    /**Убираем элемент из дочерней колонки таблицы связи*/
    public function removeFromChildRelation(int $id):void;

}