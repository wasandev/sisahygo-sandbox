<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Titasgailius\SearchRelations\SearchesRelations;

abstract class Resource extends NovaResource
{
    use SearchesRelations;
    public static $showColumnBorders = true;
    public static $tableStyle = 'tight';
    public static $perPageViaRelationship = 50;
    public static $relatableSearchResults = 100;
    public static $debounce = 0.5; // 0.5 seconds


    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }

    public static function perPageOptions()
    {
        return [25, 50, 100, 200, 250, 400];
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Support\Collection  $fields
     * @return array
     */
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        // Get proper response
        $serialized = parent::serializeForIndex($request, $fields);

        if ($request->lens && $request->lens == 'branch-branch-balance-receipt') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'order-billing-cash') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'order-banktransfer') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'order-billing-by-user') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'accounts-order-report-bill-by-day') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'accounts-order-report-by-day') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'accounts-order-report-by-branchrec') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'branch-branch-balance-bydate') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        if ($request->lens && $request->lens == 'branch-branch-balance-report') {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
            ]);
        }
        return $serialized;
    }
}
