@extends('layouts.app')

@section('meta_title', 'Our Collections — Luxury Menswear | House of KNP')
@section('meta_description', 'Explore the exclusive collections at House of KNP. Handcrafted luxury, premium fabrics, and timeless style.')

@section('content')
<style>
    .collections-page {
        background: #fff;
        color: #111;
        font-family: 'Inter', sans-serif;
        padding-bottom: 80px;
    }
    .collections-hero {
        text-align: center;
        padding: 80px 20px 50px;
        animation: fadeInDown 0.8s ease-out;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .collections-hero .eyebrow {
        color: #990000;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin-bottom: 16px;
        display: block;
    }
    .collections-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size:clamp(36px, 5vw, 56px);
        font-weight: 700;
        color: #111;
        line-height: 1.1;
        margin-bottom: 24px;
    }
    .collections-hero p {
        color: #555;
        font-size: clamp(15px, 2vw, 17px);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .collections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 0 5%;
        max-width: 1400px;
        margin: 0 auto;
    }
    @media (min-width: 768px) {
        .collections-grid {
            gap: 30px;
        }
    }
    .collection-card {
        position: relative;
        overflow: hidden;
        display: block;
        text-decoration: none;
        aspect-ratio: 3/4;
        background: #f9f9f9;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        animation: fadeInUp 0.8s ease-out both;
    }
    .collection-card:nth-child(1) { animation-delay: 0.1s; }
    .collection-card:nth-child(2) { animation-delay: 0.2s; }
    .collection-card:nth-child(3) { animation-delay: 0.3s; }
    .collection-card:nth-child(4) { animation-delay: 0.4s; }
    
    @media (min-width: 1024px) {
        .collection-card { aspect-ratio: 4/5; border-radius: 0; box-shadow: none; }
    }
    
    .collection-img-wrap {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .collection-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.02);
        transition: transform 1.2s cubic-bezier(0.19, 1, 0.22, 1);
    }
    .collection-card:hover .collection-img {
        transform: scale(1.08);
    }
    .collection-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(17, 17, 17, 0.85) 0%, rgba(17, 17, 17, 0.2) 50%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 30px 20px;
        transition: background 0.5s ease;
    }
    @media (min-width: 768px) {
        .collection-overlay { padding: 40px; }
    }
    .collection-card:hover .collection-overlay {
        background: linear-gradient(to top, rgba(17, 17, 17, 0.95) 0%, rgba(17, 17, 17, 0.4) 60%, transparent 100%);
    }
    .collection-title {
        color: #fff;
        font-family: 'Playfair Display', serif;
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 600;
        margin-bottom: 12px;
        transform: translateY(15px);
        transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }
    .collection-desc {
        color: #eaeaea;
        font-size: 15px;
        line-height: 1.5;
        margin-bottom: 25px;
        opacity: 0;
        transform: translateY(15px);
        transition: opacity 0.4s ease, transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }
    .collection-btn {
        display: inline-block;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        padding-bottom: 6px;
        width: fit-content;
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }
    
    @media (hover: hover) {
        .collection-card:hover .collection-title { transform: translateY(0); }
        .collection-card:hover .collection-desc { opacity: 1; transform: translateY(0); transition-delay: 0.1s; }
        .collection-card:hover .collection-btn { opacity: 1; transform: translateY(0); transition-delay: 0.15s; border-bottom: 1px solid rgba(255, 255, 255, 1); }
    }

    @media (hover: none), (max-width: 768px) {
        .collection-title { transform: translateY(0); }
        .collection-desc { opacity: 1; transform: translateY(0); margin-bottom: 15px; }
        .collection-btn { opacity: 1; transform: translateY(0); border-bottom: 1px solid rgba(255, 255, 255, 1); }
        .collections-hero { padding: 40px 15px 30px; }
    }
</style>

<div class="collections-page">
    <div class="collections-hero">
        <span class="eyebrow">Discover</span>
        <h1>Curated Collections</h1>
        <p>Explore our exclusive range of luxury menswear, accessories, and signature combos crafted for the modern gentleman.</p>
    </div>

    <div class="collections-grid">
        @foreach($collections as $item)
            <a href="{{ url('/category/' . \Illuminate\Support\Str::slug($item->category_name)) }}" class="collection-card">
                <div class="collection-img-wrap">
                    <img src="{{ house_category_image_url($item->category_image) }}" alt="{{ $item->category_name }}" class="collection-img" onerror="this.src='https://placehold.co/600x800/eeeeee/111111?text={{ urlencode($item->category_name) }}'">
                </div>
                <div class="collection-overlay">
                    <h3 class="collection-title">{{ $item->category_name }}</h3>
                    <p class="collection-desc">{{ 'Explore our premium ' . strtolower($item->category_name) . ' collection.' }}</p>
                    <span class="collection-btn">Explore Now</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
