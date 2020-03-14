# Todo

- [ ] RSS Duyuru
	- [x] Laravel
	- [ ] Telegram

- [ ] Ders Sayfaları Katalog Taraması
	- [x] Laravel 
	- [ ] Telegram (?)

- [ ] Ders Sayfaları Değişiklik Kontrolü
	- [x] Laravel
	- [ ] Telegram

- [ ] Telegram Kullanıcıları
	- [ ] Laravel
	- [ ] Telegram (?)


- [ ] ders sayfası veritabanına ilk defa eklendiğinde bildirim gitmesin
- [x] migration ondelete cascade
- [ ] güncellenen duyurular için, duyurunun güncellendiğine dair ayrı bir mesaj eklensin
- [ ] "/start" komutu verildiğinde "Merhaba Onur (@onuruslu)"daki  @'li kısım kullanıcı adı girmemiş olanlarda gelmiyor
- [ ] ders sayfasının linki verildiğinde olan sayfalar tekrar taranmasın sadece yeniler eklensin
- [ ] ThreeLeggedBotService.php dosyası mesaj türünü algılayan ve mesajı işleyen 2 ayrı sınıfa parçalanmalı
- [ ] birden fazla duyuru geldiğinde son duyurunun son mesajda gelmesi gerekli
- [ ] App\Facades\ThreeLeggedBotFacade, servis klasörüne alınmalı (?)
- [ ] App\Events ve App\Listeners klasörünün içindeki dosyalar, servis klasörünün içine alınmalı (?)
- [x] Callbackler command'lardan ayrılmalı
- [x] Feature test yazılmalı
- [ ] ThreeLeggedBotService::login(...) ve WebhookHandler::login(...) birleştirilmeli