@include('layouts.sidebar')

{{-- libs yang sudah umum dipakai di template-mu --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
  /* sentuhan kecil agar textarea dan list hasil enak dibaca, tetap mengikuti theme */
  .words-3col { columns: 3; column-gap: 24px; }
  .pre-like   { white-space: pre-wrap; background:#f8f9fa; border:1px dashed #e9ecef; border-radius:8px; padding:10px }
  .gap-8 > * { margin-right: .5rem; margin-bottom: .5rem; }
  @media (max-width: 992px){ .words-3col { columns: 1; }}
</style>

<div id="main-content">
  <div class="container-fluid">
    {{-- HEADER --}}
    <div class="block-header">
      <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-between">
          <h2 class="mb-0">Alat Olah Artikel</h2>
        </div>
      </div>
      <small class="text-muted">Fitur: cari kata (hitungan), ganti kata (replace), urutkan kata (A–Z).</small>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('resp_msg'))
      <div class="alert alert-success">{{ session('resp_msg') }}</div>
    @endif
    @if(session('err_msg'))
      <div class="alert alert-danger">{{ session('err_msg') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Validasi gagal</strong>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          {{-- CARD HEADER --}}
          <div class="header">
            <div class="row">
              <div class="col-12">
                <h2 class="mb-0">Editor Artikel</h2>
              </div>
            </div>
          </div>

          {{-- CARD BODY --}}
          <div class="body">
            <form method="post" action="{{ route('artikel.run') }}">
              @csrf

              {{-- ARTIKEL --}}
              <div class="form-group">
                <label for="article" class="font-weight-bold">Artikel</label>
                <textarea id="article" name="article" rows="12" class="form-control"
                          placeholder="Tempel/ubah artikel di sini.">{{ old('article', $article) }}</textarea>
                <small class="text-muted">Tempel teks artikel lalu jalankan salah satu aksi di bawah.</small>
              </div>

              {{-- AKSI: SEARCH / REPLACE / SORT --}}
              <div class="row">
                <div class="col-lg-4">
                  <div class="card mb-3">
                    <div class="header"><h2 class="mb-0">Pencarian Kata</h2></div>
                    <div class="body">
                      <div class="form-group">
                        <input type="text" class="form-control" name="keyword"
                               placeholder="mis. pendidikan" value="{{ old('keyword') }}">
                        <small class="text-muted">Whole-word, case-insensitive.</small>
                      </div>
                      <button type="submit" name="mode" value="search" class="btn btn-primary">
                        Hitung
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="card mb-3">
                    <div class="header"><h2 class="mb-0">Penggantian Kata</h2></div>
                    <div class="body">
                      <div class="form-row gap-8">
                        <div class="form-group col-12">
                          <input type="text" class="form-control" name="from"
                                 placeholder="dari (mis. adalah)" value="{{ old('from') }}">
                        </div>
                        <div class="form-group col-12">
                          <input type="text" class="form-control" name="to"
                                 placeholder="ke (mis. ialah)" value="{{ old('to') }}">
                        </div>
                      </div>
                      <small class="d-block text-muted mb-2">Whole-word, case-insensitive.</small>
                      <button type="submit" name="mode" value="replace" class="btn btn-warning">
                        Ganti
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="card mb-3">
                    <div class="header"><h2 class="mb-0">Pengurutan Kata (A–Z)</h2></div>
                    <div class="body">
                      <p class="text-muted mb-2">
                        Menghasilkan daftar kata unik (lowercase; tanda baca dibersihkan).
                      </p>
                      <button type="submit" name="mode" value="sort" class="btn btn-dark">
                        Urutkan
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            {{-- HASIL --}}
            @if ($result)
              <div class="card mt-3">
                <div class="header">
                  <h2 class="mb-0">Hasil (mode: {{ $result['type'] }})</h2>
                </div>
                <div class="body">
                  @if ($result['type'] === 'search')
                    <p>
                      Kata <strong>"{{ $result['keyword'] }}"</strong>
                      ditemukan <span class="badge badge-primary">{{ $result['count'] }}</span> kali.
                    </p>
                  @elseif ($result['type'] === 'replace')
                    <p class="mb-2">
                      Berhasil mengganti <span class="badge badge-secondary">{{ $result['from'] }}</span>
                      &rarr; <span class="badge badge-success">{{ $result['to'] }}</span>.
                    </p>
                    <div class="pre-like mb-3">{{ $result['preview'] }}</div>
                    <h6 class="mb-2">Artikel (sesudah replace)</h6>
                    <div class="pre-like">{{ $result['article'] }}</div>
                  @elseif ($result['type'] === 'sort')
                    <p>Total kata unik:
                      <span class="badge badge-info">{{ $result['total'] }}</span>
                    </p>
                    <ul class="words-3col pl-3">
                      @foreach ($result['words'] as $w)
                        <li>{{ $w }}</li>
                      @endforeach
                    </ul>
                  @endif
                </div>
              </div>
            @endif
          </div> {{-- /body --}}
        </div> {{-- /card --}}
      </div>
    </div>
  </div>
</div>
