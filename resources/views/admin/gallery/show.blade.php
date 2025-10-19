@extends('layouts.admin')

@section('title', 'Detail Galeri - Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Galeri</h1>
            <p class="text-gray-600">{{ $gallery->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.gallery.edit', $gallery) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                Edit Galeri
            </a>
            <a href="{{ route('admin.gallery.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <!-- Gallery Info -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gallery Details -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Galeri</h3>
                        
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->title }}</dd>
                            </div>
                            
                            @if($gallery->description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->description }}</dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $gallery->category_label }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $gallery->type == 'photo' ? 'bg-green-100 text-green-800' : 
                                           ($gallery->type == 'video' ? 'bg-red-100 text-red-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $gallery->type_label }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $gallery->status == 'published' ? 'bg-green-100 text-green-800' : 
                                           ($gallery->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $gallery->status_label }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Urutan Tampil</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->sort_order }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Item</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->getItemCount() }} item</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Options -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                @if($gallery->is_featured)
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-900">Galeri Unggulan</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-500">Bukan Galeri Unggulan</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center">
                                @if($gallery->is_public)
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-900">Publik</span>
                                @else
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-900">Private</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cover Image -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Cover</h3>
                    @if($gallery->cover_image)
                        <img src="{{ $gallery->cover_image_url }}" 
                             alt="{{ $gallery->title }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-300">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm">Tidak ada gambar cover</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Items -->
    @if($items->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Item Galeri ({{ $items->count() }})</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($items as $item)
                <div class="relative group">
                    @if($item->isImage())
                        <img src="{{ $item- onerror="this.src='{{ asset('images/default-gallery.png') }}'">file_url }}" 
                             alt="{{ $item- onerror="this.src='{{ asset('images/default-gallery.png') }}'">title }}" 
                             class="w-full h-24 object-cover rounded-lg border border-gray-300">
                    @else
                        <div class="w-full h-24 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    @if($item->is_featured)
                    <div class="absolute top-1 right-1">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </span>
                    </div>
                    @endif
                    
                    <div class="mt-1">
                        <p class="text-xs text-gray-600 truncate">{{ $item->title ?: 'Untitled' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->file_type }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Item Galeri</h3>
        </div>
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada item</h3>
            <p class="mt-1 text-sm text-gray-500">Galeri ini belum memiliki item.</p>
        </div>
    </div>
    @endif
</div>
@endsection
