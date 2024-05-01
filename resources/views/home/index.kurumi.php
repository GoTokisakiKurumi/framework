@kurumiSection('title')

home

@endKurumiSection

@kurumiSection('layouts')

<h1>Hello World! <?= $nama ?></h1>
{{ $nama }}
{! $nama !}

@endKurumiSection

@kurumiSection('footer')

@copyright lutfi aulia sidik

@endKurumiSection

@kurumiExtends('layout/main')
