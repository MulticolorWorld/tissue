@extends('layouts.admin')

@section('title', 'お知らせ')

@section('tab-content')
    <div class="container">
        <h2>お知らせの作成</h2>
        <hr>
        <form action="{{ route('admin.info.store') }}" method="post">
            {{ csrf_field() }}

            <div class="row">
                <div class="form-group col-12 col-lg-6">
                    <label for="category">カテゴリ</label>
                    <select id="category" name="category" class="form-control">
                        @foreach($categories as $id => $category)
                            <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $category['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-lg-6 d-flex flex-column justify-content-center">
                    <div class="custom-control custom-checkbox">
                        <input id="pinned" name="pinned" type="checkbox" class="custom-control-input" value="1"
                                {{ old('pinned') ? 'checked' : ''}}>
                        <label for="pinned" class="custom-control-label">ピン留め (常に優先表示) する</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="title">タイトル</label>
                <input id="title" name="title" type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                
                @if ($errors->has('title'))
                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                @endif
            </div>
            <div class="form-group mt-3">
                <label for="content">本文</label>
                <textarea id="content" name="content" rows="15" class="form-control  {{ $errors->has('content') ? ' is-invalid' : '' }}" maxlength="10000">{{ old('content') }}</textarea>
                <small class="form-text text-muted">
                    最大 10000 文字、Markdown 形式
                </small>

                @if ($errors->has('content'))
                    <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">登録</button>
            </div>
        </form>
    </div>
@endsection