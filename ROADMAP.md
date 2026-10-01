# ROADMAP

Piano di miglioramento per `javanile/php-imap2`, una libreria PHP che implementa le funzioni `imap_*()` senza richiedere l'estensione C-IMAP, con supporto OAuth2.

---

## Priorità: Critica (bug e blocchi)

### 1. Duplicato in `composer.json`

**File:** `composer.json:25-27`

La dipendenza `zbateson/mail-mime-parser` è dichiarata due volte con versioni diverse. Manca una virgola alla fine della prima riga, causando un errore di parsing di Composer.

**Fix:** Unificare in un'unica riga: `"zbateson/mail-mime-parser": "^2.2 || ^3.0 || ^4.0"` e aggiungere la virgola mancante.

---

### 2. `Mail::send()` completamente rotto

**File:** `src/Mail.php:87-109`

Il metodo `send()` referenzia variabili inesistenti (`$imap`, `$options`, `$sequence`, `$flag`) invece dei parametri reali (`$to`, `$subject`, `$message`). Sembra un copia-incolla da `setFlagFull()`.

**Fix:** Riscrivere il metodo per inviare email correttamente, o rimuoverlo e lasciare che generi un errore chiaro.

---

### 3. `subscribe`, `unsubscribe`, `getSubscribed`, `listSubscribed` eliminano le mailbox

**File:** `src/Mailbox.php:313-355`

Tutti e quattro i metodi chiamano `$client->deleteFolder($mailbox)` invece delle rispettive operazioni corrette. **Pericoloso:** chiamare `imap2_subscribe()` elimina la mailbox.

**Fix:** Sostituire `deleteFolder()` con le chiamate appropriate:
- `subscribe()` → `client->subscribe()`
- `unsubscribe()` → `client->unsubscribe()`
- `getSubscribed()` → `client->listSubscribed()`
- `listSubscribed()` → `client->listSubscribed()`

---

### 4. `Functions::isRetrofitConnection` dichiarato due volte

**File:** `src/Functions.php:193-195`

Il metodo è dichiarato due volte: la seconda sovrascrive la prima e manca la parentesi di chiusura e la logica completa.

**Fix:** Unificare le due dichiarazioni in un unico metodo coerente.

---

### 5. `Polyfill::rfc822WriteHeaders` dichiarato due volte

**File:** `src/Polyfill.php:83-103`

Il metodo è dichiarato due volte con implementazioni diverse. La seconda sovrascrive la prima.

**Fix:** Unificare in un'unica implementazione.

---

### 6. `bootstrap.php::imap2_headerinfo` ha un blocco `if` senza parentesi

**File:** `bootstrap.php:857-859`

Il blocco `if (Functions::isRetrofitConnection($imap))` a riga 857 non ha la parentesi di chiusura `}`. Il codice successivo (riga 859) è eseguito comunque, creando un bug logico.

**Fix:** Aggiungere `}` a riga 858 e ristrutturare la logica condizionale correttamente.

---

### 7. `Connection::connect()` ha problemi di indentazione e logica

**File:** `src/Connection.php:164-177`

Il blocco `if (empty($this->currentMailbox))` ha indentazione confusa e variabili sovrascritte (`$mailboxes` a riga 165 viene sovrascritta a riga 167). Potrebbe causare comportamenti imprevedibili.

**Fix:** Ristrutturare la logica di connessione e selezione della mailbox iniziale.

---

### 8. `Message::fetchBody()` chiama `$client->fetch()` due volte

**File:** `src/Message.php:191-192`

La prima chiamata a `$client->fetch()` con `BODY[...]` viene immediatamente sovrascritta dalla seconda chiamata con `BODY.PEEK[...]`. La prima è sprecata.

**Fix:** Unificare in un'unica chiamata usando la chiave corretta in base al flag `FT_PEEK`.

---

### 9. `SearchTest::testSortSearch()` termina con `die()`

**File:** `tests/SearchTest.php:108`

Il test ha una chiamata `die()` che blocca l'esecuzione, impedendo al test di completarsi e di verificare i risultati.

**Fix:** Rimuovere `die()` e fare in modo che il test verifichi effettivamente i risultati.

---

## Priorità: Alta (funzionalità mancanti)

### 10. Implementare ACL

**File:** `src/Acl.php` (classe vuota)

Le funzioni `imap_getacl()`, `imap_setacl()`, `imap_get_quota()`, `imap_set_quota()`, `imap_get_quotaroot()` non sono implementate.

**Fix:** Implementare i metodi ACL nella classe `Acl.php` e aggiungere le corrispondenti funzioni in `bootstrap.php`.

---

### 11. Implementare `imap_mail_compose()`

**File:** `src/Polyfill.php` — `mailCompose()` ritorna sempre `false`

La funzione `Polyfill::mailCompose()` non fa nulla e ritorna `false`.

**Fix:** Implementare la composizione di messaggi MIME secondo la specifica di `imap_mail_compose()`.

---

### 12. Implementare `imap_sort()` correttamente

**File:** `src/Message.php` — `sort()` chiama `search()` e inverte l'array

L'implementazione di `sort()` non usa il comando IMAP `SORT` ma simula l'ordinamento lato PHP. Le opzioni di ordinamento non sono gestite correttamente.

**Fix:** Implementare il comando IMAP `SORT` tramite `ImapClient` per ordinamenti server-side reali.

---

### 13. Supporto FT_PEEK incompleto

In vari punti del codice il flag `FT_PEEK` non viene rispettato correttamente. Alcuni percorsi usano `BODY.PEEK` hardcoded indipendentemente dal flag.

**Fix:** Verificare e correggere tutte le occorrenze di `BODY` e `BODY.PEEK` in `Message.php` per rispettare `FT_PEEK`.

---

### 14. Funzioni mancanti in `bootstrap.php`

Diversi alias `imap_*()` non sono definiti. Verificare la copertura completa di tutte le 83 funzioni documentate in `docs/functions.md` e aggiungere quelle mancanti.

---

## Priorità: Media (qualità del codice)

### 15. Aggiungere type hints e return types

**File:** Tutti i file in `src/`

Molti metodi mancano di type hints e return type declarations. Aggiungerli per migliorare la robustezza del codice (compatibile con PHP 7.0+).

---

### 16. Rimuovere l'operatore `@` per la soppressione degli errori

L'operatore `@` è usato estesamente in vari file. Dovrebbe essere sostituito con una gestione esplicita degli errori.

---

### 17. Aggiungere documentazione PHPDoc completa

**File:** Tutti i file in `src/`

Molti metodi hanno PHPDoc minimale o assente. Aggiungere descrizioni, `@param` e `@return` per tutti i metodi pubblici.

---

### 18. Ristrutturare `bootstrap.php`

**File:** `bootstrap.php` (1632 righe)

Il file è molto lungo e ripetitivo. Potrebbe essere suddiviso in file più piccoli (es. `functions/`, `constants.php`) per migliorare la manutenibilità.

---

### 19. Aggiungere PHP CS Fixer o PHP CodeSniffer

Non c'è un file di configurazione per il code style. Aggiungere `.php-cs-fixer.dist.php` o `phpcs.xml` per garantire uno stile coerente.

---

### 20. Configurare PHPStan correttamente

**File:** Nessun file di configurazione PHPStan

`phpstan` è già una dipendenza dev ma non ha un file di configurazione (`phpstan.neon`).「

**Fix:** Creare `phpstan.neon` e integrarlo nel workflow CI.

---

### 21. Correggere `src/README.md` (namespace errato)

**File:** `src/README.md`

Referenzia il namespace `PhpImap2\Connection` che non esiste. Correggere in `Javanile\Imap2\Connection`.

---

## Priorità: Bassa (test e CI)

### 22. Attivare il linter CI sul branch `main`

**File:** `.github/linter.yml`

Il workflow linter è attivato solo sul branch `test2`. Spostare su `main`.

---

### 23. Rimuovere il target `release` dal Makefile

**File:** `Makefile` — target `release`

Il target esegue `git add . && git commit -am "Test CI" && git push` che è pericoloso perché committa e spinge tutto automaticamente.

**Fix:** Rimuovere o proteggere con conferma esplicita.

---

### 24. Aggiungere `.gitignore` per `composer.lock`

`composer.lock` non dovrebbe essere ignorato per una libreria (è buona norma tenerlo tracciato solo per applicazioni), ma va deciso e documentato esplicitamente.

---

### 25. Aumentare la copertura dei test

Molti test in `CompatibilityTest.php` sono placeholder che fanno solo `$this->assertEquals(1, 1)`. Altri test sono commentati. Aggiungere test reali per:
- `imap_listscan()`
- `imap_move()`
- `imap_setflagfull()` / `imap_clearflagfull()`
- `imap_sort()` (sostituire il placeholder)
- `imap_thread()`
- `imap_savebody()`
- `imap_fetchheader()`
- Operazioni con flag `FT_UID` / `CP_UID`

---

### 26. Aggiungere test per la modalità retrofit

La modalità retrofit (che passa al C-IMAP nativo) ha copertura minima. Aggiungere test che verifichino il corretto fallthrough.

---

### 27. Rimuovere test fixture inutilizzati

**File:** `tests/fixtures/`

Ci sono 40 file `.eml` ma molti non sono referenziati dai test. Fare pulizia e tenere solo quelli usati.

---

### 28. Aggiungere integrazione continua con code coverage

Configurare GitHub Actions per generare e pubblicare report di code coverage (es. Codecov o Coveralls).

---

### 29. Aggiornare la compatibilità PHP

**File:** `composer.json`

La libreria richiede PHP >= 7.0, ma molte dipendenze moderne richiedono versioni più recenti. Valutare il supporto per PHP 8.0+ e aggiornare `phpunit` di conseguenza.

---

### 30. Rimuovere `tests/legacy/`

**File:** `tests/legacy/` (3 script)

Sono script manuali non integrati in PHPUnit. Vanno rimossi o convertiti in test veri.

---

## Legenda

| Priorità | Significato |
|----------|-------------|
| Critica  | Blocca l'uso della libreria o causa comportamenti errati/pericolosi |
| Alta     | Funzionalità mancanti rispetto all'API `imap_*()` standard |
| Media    | Miglioramenti di qualità, manutenibilità e robustezza del codice |
| Bassa    | Miglioramenti opzionali: test, CI, documentazione |