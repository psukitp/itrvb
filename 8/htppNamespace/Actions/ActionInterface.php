<?php
namespace htppNamespace\Actions;

use htppNamespace\Request;
use htppNamespace\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}