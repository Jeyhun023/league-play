<?php

namespace App\Http\Requests\Front\Home;

use App\Models\Championship;
use App\Http\ViewSet\Front\Home\IndexViewSet;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * @var IndexViewSet
     */
    protected $viewSet;

    public function getViewSet(Championship $championship): IndexViewSet
    {
        if ($this->viewSet === null) {
            $this->viewSet = new IndexViewSet($this, $championship);
        }

        return $this->viewSet;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
