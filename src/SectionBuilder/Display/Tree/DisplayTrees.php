<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 01.10.2018
 * Time: 13:12
 */

namespace Zeus\Admin\SectionBuilder\Display\Tree;

use Zeus\Admin\Cms\Helpers\MenuHelper;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use ZeusAdminHelper;

class DisplayTrees
{
    private $pagination, $userPagination, $columns, $scopes, $meta, $nav, $filter, $filterPosition;

    public function __construct($columns, $pagination)
    {



        $this->setColumns($columns);
        $this->meta = new Meta;
        $this->meta->setStyles([
            'sortable-css' => asset('packages/zeusAdmin/js/jquery-ui/sortable.css'),
        ])->setScripts([
            'head' => [],
            'body' => [
                'sortable-js' => asset('packages/zeusAdmin/js/jquery-ui/sortableCategory.js'),
            ]
        ]);
        $this->setFilterPosition(config('zeusAdmin.display_table_filter_default_position') ?? 'top');
    }

    /**
     * @param mixed $pagination
     * @return DisplayTable
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPagination()
    {
        return $this->userPagination;
    }

    /**
     * @param mixed $userPagination
     */
    public function setUserPagination($userPagination)
    {
        $this->userPagination = $userPagination;
        return $this;
    }

    /**
     * @return mixed
     */


    /**
     * @param mixed $columns
     * @return DisplayTable
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param mixed $scope
     * @return DisplayTable
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @param mixed $meta
     * @return DisplayTable
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return mixed
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    public function getNav()
    {
        return $this->nav;
    }

    /**
     * @param mixed $nav
     * @return DisplayTable
     */
    public function setNav($nav)
    {
        $this->nav = $nav;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param mixed $filter
     * @return DisplayTable
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterPosition()
    {
        return $this->filterPosition;
    }

    /**
     * @param mixed $filterPosition
     */
    public function setFilterPosition($filterPosition)
    {
        $this->filterPosition = $filterPosition;
        return $this;
    }

    public function render($modelPath, Section $firedSection, $pluginData = null, $request = null)
    {
        $columns = $this->getColumns();
        $relationData = null;

        foreach ($columns as $column)
        {
            $exp = explode('.', $column->getName());
            if(count($exp) > 1)
            {
                $relationData[] = implode(".", array_slice($exp, 0, -1));
            }
        }

        $model = new $modelPath();

        if(!empty($this->getScopes()))
        {
            foreach ($this->getScopes() as $scope)
            {
                $model = $model->{$scope}();
            }
        }

        $data = $model
            ->when(isset($relationData), function ($query) use ($relationData) {
                $query->with($relationData);
            })
            ->when(!empty($request->sort), function ($query) use ($request) {
                parse_str($request->sort, $sortArray);
                foreach ($sortArray as $sortItem) {
                    $query = $query->orderBy($sortItem['by'], $sortItem['type']);
                }
            })
            ->when(empty($request->sort), function ($query) use ($request) {
                $query = $query->orderBy('id', 'desc');
            })
            ->when(!empty($request->filter), function ($query) use ($request) {
                parse_str($request->filter, $filterArray);
                foreach ($filterArray as $filterItem) {
                    if($filterItem['is_like'] == '1') {
                        $query = $query->where($filterItem['field'], 'like', '%' . $filterItem['value'] . '%');
                    } else {
                        $query = $query->where($filterItem['field'], $filterItem['value']);
                    }
                }
            });

        $data = $data->get()->toTree();


        $fields = array();

        foreach ($data as $key => $row)
        {
            foreach ($columns as $column)
            {
                $names = explode('.', $column->getName());

                $rowVal = $row;
                foreach ($names as $name)
                {
                    if(!(is_array($rowVal) || $rowVal instanceof \Countable))
                    {
                        $rowVal = $rowVal->{$name} ?? null;
                    } else
                    {
                        break;
                    }
                }

                $fields[$key][$column->getName()] = $column->render($rowVal);
            }
            $fields[$key]['brRowId'] = $row->id;
        }

        if(isset($pluginData['redirectUrl']))
        {
            $rc = new \ReflectionClass($firedSection);
            $pluginData['redirectUrl'] = strtr($pluginData['redirectUrl'], ['{sectionName}' => $rc->getShortName()]);
        }

        $nav = self::getNav();
        $filter = $this->getFilter();
        $filterPosition = $this->getFilterPosition();



        $response['data'] = $data;
        $response['view'] = View::make('zeusAdmin::SectionBuilder/Display/Tree/tree')
            ->with(compact(
                'data',
                'columns',
                'fields',
                'firedSection',
                'pluginData',
                'nav',
                'filter',
                'filterPosition',
                'modelPath'
            ));

        return $response;
    }
}