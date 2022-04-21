# PHP Corvass Client

Bu paket, hem XML hem Http API ile çalışan kullanımı kolay bir Corvass servisi sağlar.

This package provides an easy to use Corvass service which can be used with both XML and Http apis.
For the English version: [README](README.md)

## Contents

- [Kurulum](#kurulum)
    - [Corvass Servisinin Kurulmasi](#jetsms-servisinin-kurulmasi)
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
composer require erdemkeren/corvass-php
```

### Corvass Servisinin Kurulmasi

Corvass servisini kullanabilmek için kayıt olunmalı ve kontör satın alınmalı. 

## Kullanim

Önce, CorvassService sınıfı, istenilen istemci uyarlaması kullanarak çalıştırılır.

- **CorvassXmlClient**
- **CorvassHttpClient** (Bu daha ziyade Rest servisi gibi ama HTTP demeyi tercih etmiş.)

```php
require __DIR__ . '/../vendor/autoload.php';

use BahriCanli\Corvass\CorvassService;
use BahriCanli\Corvass\ShortMessageFactory;
use BahriCanli\Corvass\Http\Clients\CorvassXmlClient;
use BahriCanli\Corvass\Http\Clients\CorvassHttpClient;
use BahriCanli\Corvass\ShortMessageCollectionFactory;

$service = new CorvassService(new CorvassXmlClient(
    'www.biotekno.biz:8080/SMS-Web/xmlsms',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());

// ya da

$service = new CorvassService(new CorvassHttpClient(
    new GuzzleHttp\Client(),
    'https://service.jetsms.com.tr/SMS-Web/HttpSmsSend',
    'username',
    'password',
    'outboxname'
), new ShortMessageFactory(), new ShortMessageCollectionFactory());
```

### Metotlar

CorvassService örneğini başarıyla çalıştırdıktan sonra; aşağıda bulunan metotlardan birini kullanarak SMS(ler) göndermeye başlayabilirsiniz.

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

Eğer istemci olarak `CorvassHttpClient` sınıfı kullanılıyorsa `$response->groupId()` çağrısı istisnaya sebep olur.
Eğer istemci olarak `CorvassXmlClient` sınıfı kullanılıyorsa `$response->messageReportIdentifiers()` çağrısı istisnaya sebep olur.

İstemci uyarlamasını değiştirirken temkinli olun.

## Degisiklik Listesi

Lütfen son değişiklikleri görmek için [Değişiklik Listesi](DEGISIKLIKLER.md) dosyasını ziyaret ediniz.


## Test

``` bash
$ composer test
```

## Güvenlik

Bu paket, Corvass tarafından sağlanan servisleri kullanmaktadır. Eğer istemci taraflı bir güvenlik açığı bulduysanız; lütfen
yeni bir ticket açmak yerine geliştiriciye e-posta atın.

## Katkıda Bulunun

Eğer katkıda bulunmak isterseniz lütfen [Katkıda Bulunun](KATKI.md) dosyasını inceleyin.

## Tanıtımlar

- [Bahri Meriç Canlı](https://github.com/erdemkeren)

## Lisans

The MIT License (MIT). Detaylar için lütfen [Lisans Dosyasını](LISANS.md) inceleyin.
