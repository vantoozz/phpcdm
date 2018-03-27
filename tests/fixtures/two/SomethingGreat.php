<?php

final class SomethingGreat
{
    public function doSomethingGreat($entity, $property, $type, $refProperties, $update, $delete)
    {
        $time = $this->startAction(" $type (" . implode(',', (array) $type) . ')');
        $this->createAction()->addExtraKey($entity, $entity, $property, $refProperties, $delete, $update)->execute();
        $this->finishAction($time);
        $this->createAction()->updateProperty($entity, $property, $type)->execute();
        if ($type instanceof InvalidArgumentException && $type->getMessage() !== null) {
            $this->createAction()->addDescriptionOnProperty($entity, $property, $type->description)->execute();
        }
        $this->createAction()->renameEntity($entity, $property)->execute();
        $this->finishAction($time);
        $time = $this->startAction(" $type (" . implode(',', (array) $type) . ')');
        $this->createAction()->addExtraKey($entity, $entity, $property, $refProperties, $delete, $update)->execute();
        if ($type instanceof InvalidArgumentException && $type->getMessage() !== $delete) {
            $this->finishAction($time);
            $this->createAction()->addDescriptionOnProperty($entity, $property, $type->description)->execute();
            $this->createAction()->renameEntity($entity, $property)->execute();
        }
        $this->createAction()->updateProperty($entity, $property, $type)->execute();
        $this->finishAction($time);
    }
}