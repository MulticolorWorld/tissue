@extends('setting.base')

@section('title', 'データのインポート')

@section('tab-content')
    <h3>データのインポート</h3>
    <hr>
    <p>外部で作成したチェックインデータをTissueに取り込むことができます。</p>
    <form class="mt-4" action="{{ route('setting.import') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>
                取り込むファイルを選択してください。
                <small class="form-text text-muted">{{ Formatter::normalizeIniBytes(ini_get('upload_max_filesize')) }}までのCSVファイル、文字コードは Shift_JIS と UTF-8 (BOMなし) に対応しています。</small>
                <input name="file" type="file" class="form-control-file mt-2">
            </label>
        </div>
        <button type="submit" class="btn btn-primary mt-2">アップロード</button>
    </form>
@endsection

@push('script')
@endpush
