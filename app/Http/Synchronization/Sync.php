<?php

namespace App\Http\Synchronization;


final class Sync
{

    private static $modelDest = '\App\Models\\';

    private static $model;

    private static $destination;


    public function __construct(string $destination, string $model)
    {
        self::$model       = $model;
        self::$destination = $destination;

    }//end __construct()


    private static function getModelName()
    {
        return self::$model;

    }//end getModelName()


    private static function getModelDestination()
    {
        return self::$destination;

    }//end getModelDestination()


    private static function fullDestination()
    {
        return self::$modelDest.self::getModelDestination().'\\'.ucfirst(self::getModelName());

    }//end fullDestination()


    private static function getCurrentSession() : array
    {
         $currentSession = \Session::get('local_headers');
         return $currentSession;

    }//end getCurrentSession()


    private static function getResponse()
    {
        $modelResponse = \Http::connectTimeout(3000)->withHeaders(self::getCurrentSession())->get(str_replace(env('APP_URL'), env('SECOND_APP_URL'), route(self::getModelName().'api.index')));
        return $modelResponse;

    }//end getResponse()


    public static function upsertData()
    {
        $fullDestination = self::fullDestination();
        $upsertOrCreate  = $fullDestination::upsert(self::getResponse()['all'], ['code']);
        if ($upsertOrCreate === false) {
            return false;
        }

    }//end upsertData()


    public static function deleteIncrements()
    {
        $fullDestination  = self::fullDestination();
        $deleteIncrements = $fullDestination::whereNotIn('id', self::getResponse()['ids'])->delete();
        if ($deleteIncrements === false) {
            return false;
        }

    }//end deleteIncrements()


}//end class
