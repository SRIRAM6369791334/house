<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function productImage($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dash/public/images/product_images/'.$filename),
            base_path('../dash/public/images1/product_images/'.$filename),
            public_path('storage/product_images/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function productMedia($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dash/public/images/product_images/'.$filename),
            base_path('../dash/public/images1/product_images/'.$filename),
            base_path('../dash/public/images/product_images1/'.$filename),
            base_path('../dash/public/images1/product_images1/'.$filename),
            base_path('../dash/public/images/varient_images/'.$filename),
            base_path('../dash/public/images1/varient_images/'.$filename),
            base_path('../dash/public/images/product_child_images/'.$filename),
            base_path('../dash/public/images1/product_child_images/'.$filename),
            base_path('../dash/public/storage/varient_images/'.$filename),
            base_path('../dash/public/storage/variant_images/'.$filename),
            base_path('../dash/public/storage/product_child_images/'.$filename),
            base_path('../dash/storage/app/public/varient_images/'.$filename),
            base_path('../dash/storage/app/public/variant_images/'.$filename),
            base_path('../dash/storage/app/public/product_child_images/'.$filename),
            public_path('storage/product_images/'.$filename),
            public_path('storage/varient_images/'.$filename),
            public_path('storage/variant_images/'.$filename),
            public_path('storage/product_child_images/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function categoryImage($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dashhboardhouse/public/images/category_images/'.$filename),
            base_path('../dash/public/images/category_images/'.$filename),
            base_path('../dash/public/images1/category_images/'.$filename),
            base_path('../dash/public/app/public/category_images/'.$filename),
            base_path('../dash/storage/app/public/category_images/'.$filename),
            public_path('storage/category_images/'.$filename),
            public_path('images/category_images/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function categoryBanner($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dashhboardhouse/public/images/category_banners/'.$filename),
            base_path('../dash/public/images/category_banners/'.$filename),
            base_path('../dash/public/images1/category_banners/'.$filename),
            public_path('storage/category_banners/'.$filename),
            public_path('images/category_banners/'.$filename),
            public_path('images/banner/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function webImage($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dash/public/images/web_images/'.$filename),
            base_path('../dash/public/images1/web_images/'.$filename),
            base_path('../dash/public/app/public/web_images/'.$filename),
            base_path('../dash/storage/app/public/web_images/'.$filename),
            public_path('storage/web_images/'.$filename),
            public_path('images/web_images/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function webVideo($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dash/storage/app/public/web_videos/'.$filename),
            base_path('../dash/public/storage/web_videos/'.$filename),
            base_path('../dash/public/images/web_videos/'.$filename),
            public_path('storage/web_videos/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        abort(404);
    }

    public function instagramImage($filename)
    {
        abort_if(str_contains($filename, '..'), 404);

        foreach ([
            base_path('../dash/public/images/home_promotions/'.$filename),
            base_path('../dash/public/images1/home_promotions/'.$filename),
            base_path('../dash/public/app/public/home_promotions/'.$filename),
            base_path('../dash/storage/app/public/home_promotions/'.$filename),
            public_path('storage/home_promotions/'.$filename),
            public_path('images/home_promotions/'.$filename),
        ] as $path) {
            if (File::exists($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function upload($path)
    {
        abort_if(str_contains($path, '..'), 404);

        foreach ([
            base_path('../dashhboardhouse/public/uploads/'.$path),
            base_path('../dash/public/uploads/'.$path),
            public_path('uploads/'.$path),
            storage_path('app/public/'.$path),
        ] as $filePath) {
            if (File::exists($filePath)) {
                return response()->file($filePath, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        abort(404);
    }

    public function blogImage($filename)
    {
        $path = base_path('../dash/public/uploads/blogs/'.$filename);

        if (File::exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }
}
