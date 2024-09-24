<?php

namespace App\Export\Xlsx\Views\Columns\Actions;

enum EnumRunTime:string
{
    case BEFORE_GENREATE_BODY = "beforeGenerateBody";
    case AFTER_GENERATE_BODY = "afterGenerateBody";
    case GENERATE_BODY = "generateBody";
}
