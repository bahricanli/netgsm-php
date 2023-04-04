# PHP NetGsm Client

Bu paket, hem XML hem Http API ile çalışan kullanımı kolay bir netgsm servisi sağlar.

This package provides an easy to use netgsm service which can be used with both XML and Http apis.
For the English version: [README](README.md)

## Contents

- [Kurulum](#kurulum)
    - [netgsm Servisinin Kurulmasi](#netgsm-servisinin-kurulmasi)
- [Kullanim](#kullanim)
    - [Metotlar](#metotlar)
- [Degisiklik Listesi](#degisiklik-listesi)
- [Test](#test)
- [Guvenlik](#guvenlik)
- [Katkida Bulunmak](#katkida-bulunmak)
- [Jenerik](#jenerik)
- [Lisans](#lisans)

## Kurulum

Bu paket, composer kullanılarak kurulabilir.

``` bash
composer require bahricanli/netgsm-php
```

### netgsm Servisinin Kurulmasi

netgsm servisini kullanabilmek için kayıt olunmalı ve kontör satın alınmalı. 

## Kullanim

Önce, netgsmService sınıfı, istenilen istemci uyarlaması kullanarak çalıştırılır.

- **netgsmXmlClient**
- **netgsmHttpClient** (Bu daha ziyade Rest servisi gibi ama HTTP demeyi tercih etmiş.)

```php
require __DIR__ . '/../vendor/autoload.php';

use BahriCanli\netgsm\netgsmService;
use BahriCanli\netgsm\ShortMessageFactory;
use BahriCanli\netgsm\Http\Clients\netgsmXmlClient;
use BahriCanli\netgsm\Http\Clients\netgsmHttpClient;
use BahriCanli\netgsm\ShortMessageCollectionFactory;

$service = new netgsmService(new netgsmXmlClient(
    'sms.netgsm.net/xml',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());

// ya da

$service = new netgsmService(new netgsmHttpClient(
    new GuzzleHttp\Client(),
    'https://sms.netgsm.net/http',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());
```

### Metotlar

netgsmService örneğini başarıyla çalıştırdıktan sonra; aşağıda bulunan metotlardan birini kullanarak SMS(ler) göndermeye başlayabilirsiniz.

#### Tek Mesaj - Bir ya da Daha Çok Alıcı

```php
$response = $service->sendShortMessage(['5530000000', '5420000000'], 'Bu bir test mesajıdır.');

if($response->isSuccessful()) {
    // storeGroupIdForLaterReference fonksiyonu pakete dahil değildir.
    storeGroupIdForLaterReference($response->groupId());
} else {
    var_dump($response->message());
    var_dump($response->statusCode());
    var_dump($response->status());
}
```

#### Çoklu Mesaj - Çoklu Alıcı:

Eğer bu yöntemi kullanıyorsanız, her mesajın yalnızca bir alıcısı olmalıdır. _(Bu da hacklemediğim bir API kısıtıdır.)_

```php
$response2 = $service->sendShortMessages([[
    'recipient' => '5530000000',
    'message' => 'This is a test.',
], [
    'recipient' => '5420000000',
    'message' => 'This is another test.',
]]);

if($response2->isSuccessful()) {
    // storeGroupIdForLaterReference fonksiyonu pakete dahil değildir.
    storeGroupIdForLaterReference($response2->groupId());
} else {
    var_dump($response2->message());
    var_dump($response2->statusCode());
    var_dump($response2->status());
}
```

### Dipnot

Eğer istemci olarak `netgsmHttpClient` sınıfı kullanılıyorsa `$response->groupId()` çağrısı istisnaya sebep olur.
Eğer istemci olarak `netgsmXmlClient` sınıfı kullanılıyorsa `$response->messageReportIdentifiers()` çağrısı istisnaya sebep olur.

İstemci uyarlamasını değiştirirken temkinli olun.

## Degisiklik Listesi

Lütfen son değişiklikleri görmek için [Değişiklik Listesi](DEGISIKLIKLER.md) dosyasını ziyaret ediniz.


## Test

``` bash
$ composer test
```

## Güvenlik

Bu paket, netgsm tarafından sağlanan servisleri kullanmaktadır. Eğer istemci taraflı bir güvenlik açığı bulduysanız; lütfen
yeni bir ticket açmak yerine geliştiriciye e-posta atın.

## Katkıda Bulunun

Eğer katkıda bulunmak isterseniz lütfen [Katkıda Bulunun](KATKI.md) dosyasını inceleyin.

## Tanıtımlar

- [Bahri Meriç Canlı](https://github.com/bahricanli)

## Lisans

The MIT License (MIT). Detaylar için lütfen [Lisans Dosyasını](LISANS.md) inceleyin.
