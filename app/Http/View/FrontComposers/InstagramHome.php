<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 27.01.19
 * Time: 11:51
 */

namespace App\Http\View\FrontComposers;

use App\Cart\ProductCart;
use Illuminate\View\View;

class InstagramHome
{
    /**
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        $res = [];
        if (variable('insta_username')) {
            $res = \Cache::remember('insta_photos', variable('insta_photo_cache', 30), function () {
                $instagram = new \InstagramScraper\Instagram();
                try {
                    $nonPrivateAccountMedias = $instagram->getMedias(variable('insta_username'), variable('insta_photo_count', 30));

                    foreach ($nonPrivateAccountMedias as $media) {
                        $res[] = $media->getImageThumbnailUrl();
                    }

                    return $res;
                } catch (\Exception $exception) {
                    \Log::error($exception);

                    return [];
                }
            });
        }

        $view->with('iPhotos', collect($res));
    }
}