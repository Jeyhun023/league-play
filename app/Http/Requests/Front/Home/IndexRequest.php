<?php

namespace App\Http\Requests\Front\Home;

use App\Http\ViewSet\Front\Home\IndexViewSet;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * @var IndexViewSet
     */
    protected $viewSet;

    public function getViewSet(): IndexViewSet
    {
        if ($this->viewSet === null) {
            $this->viewSet = new IndexViewSet($this);
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
