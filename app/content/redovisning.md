Kmom01: PHP-baserade och MVC-inspirerade ramverk
------------------------------------

Oj, oj, oj... mitt första intryck var att Anax-MVC var ett otroligt krångligt sätt att göra hemsidor. Eller åtminstone väldigt svårt att förstå för någon som aldrig har sneglat på ramverk förut.
Det är lätt att förstå tanken och filosofin bakom det här med att separera saker, var sak på sin plats etc, men att försöka förstå koden i praktiken blev genast svårare!
Efter lite huvudvärk fick jag i alla fall till en me-sida ... och efter ytterligare (inte så lite) funderande fick jag mitt gamla tärningsspel från oophp att fungera också.
Det krävde att jag provade ett otal olika namnrymder på tärningsspelets klasser och när jag äntligen fick det att fungera så var det med "Mos" som namnrymd. Det tyder nog på att det går att definiera specifika "namespace" någonstans?! Sedan tog det en stund innan jag fick spelet att fungera fullt ut, men det var inte lika svårt. Funktionen $app->request->getGet() returnerar värdet (t.ex. 'roll') och inte "true" om villkoret är sant, vilket krävde lite ändringar för att ta hand om inkommande argument i koden på frontkontrollern.

**Markdown och bildsökvägar.** All text skulle ju skrivas med markdown och jag hittade ett inlägg i forumet om *shortcodes*, som jag kunde använda för att infoga bilder och bildtext, komplett med css-klasser etc. i markdown-filerna. Tyvärr måste man använda absoluta sökvägar (om t.ex. samma byline-markdown-fil används i både i "hem-routen" "me" och i routen "redovisning", så blir inte den relativa bildsökvägen densamma i båda fallen). "Foiki" försökte hjälpa mig i chatten och tipsade om att jag kunde använda $this->url->asset() i "mallen" redovisning.tpl.php i stället, men jag valde till slut att använda shortcodes direkt i markdown-filerna för att lägga in bilder med kompletta bildlänkar.

**Snygga länkar.** Jag följde instruktionerna, men tyckte ändå inte att länkarna visades som de skulle överallt. Fick hjälp via forumet och det löste sig till slut. Har även lagt en länk till startsidan i headern, som numera visas snyggt och korrekt.

**Utvecklingsmiljö och tidigare erfarenhet.** Jag använder fortfarande samma miljö som i htmlphp och oophp, dvs. de rekommenderade JEdit,  FileZilla och wamp-server på Windows 7 (har precis uppgraderat wamp-servern till senaste version). Som framgår av mitt inledande "oj, oj" ovan är jag inte överhuvudtaget bekant med ramverk sedan tidigare och inte heller med de begrepp som introduceras. Min första intryck av Anax-MVC just nu är alltså att det är oerhört krångligt. Men jag tycker inte att jag kan ha någon grundad uppfattning ännu. Jag inser förstås att om den samlade expertisen anser att detta är vägen att gå, så bör jag göra mitt yttersta för att förstå hur Anax-MVC hänger ihop. Jag återkommer med en uppfattning när jag lärt mig tillräckligt mycket för att ha en sådan.

**Extra.** Jag lade till tärningsspelet från kmom02 i oophp.

 
Kmom02: Kontroller och Modeller
------------------------------------
 
**Klar med kmom02.** Jag läste hela det rekommenderade materialet och det var en hel del nytt att försöka greppa. När det är så mycket nya termer och begrepp som är helt obekanta, blir det väldigt svårt att ta in allting. Jag gjorde som jag brukar, alltså att jag gjorde övningarna samtidigt som jag läste. Finns det inte konkreta saker att hänga upp den nya informationen på så fastnar den inte. Inte på mig i alla fall :-). 

Hade lite problem med att förstå hur jag skulle kommunicera via prompten med studentservern, hade inte förstått att Putty även fungerar som terminal. Trodde att den bara skötte anslutningen via SSH. Fick hjälp via forumet.

**Composer.** När jag väl hade löst de inledande problemen var det enkelt att installera och använda Composer lokalt. På studentservern fanns den ju redan installerad.  

**Packagist**. Jag tittade runt lite grann och hittade ett "paket" för att visa "time-ago" i kommentarerna. Valde ett annat paket först, men den laddade ned en otrolig massa filer, så jag fick lära mig hur man tar bort paketen också. Bara att radera den aktuella require-raden i composer.json-filen och köra en update. Jättesmidigt. Jag lär absolut återkomma till Packagist!

**Klasser som kontroller.** Det var en väldigt hög tröskel att komma på banan den här gången. Jag förstod inte alls hur eller var jag skulle börja. Jag är fortfarande inte helt säker på vad det betyder att använda klasser som kontroller... är det att man visar vyer direkt från klassen och inte gör det via frontkontrollern? Tjänster som dispatchas... jag måste ju ha gjort allt detta för att komma i mål, men begreppen är lite luddiga ännu. Det klarnar nog efterhand.

Jag började med det som jag kunde greppa, frontkontrollern och formulären. När jag väl hade fattat hur man kunde använda router för att hitta funktioner i CommentController-klassen, började det lossna lite. Det märkliga var att sedan rullade det på rätt bra. Nu i efterhand är jag rätt nöjd med min lösning och jag har bra grepp om det mesta som momentet omfattar.

Det största problemet jag hade (förutom startsträckan) var att jag ville visa ändringsformuläret på samma ställe som inläggsformuläret. Inifrån CommentController hade jag inte tillgång till den aktuella sidans vy och innehåll. Lösningen var till slut att från början spara den aktuella sidans innehåll i sessionsvariabler med hjälp av en extra *$app->dispatcher->forward...* osv. i index.php för varje aktuell sida. Denna *dispatcher* anropar en funktion i CommentController som i sin tur sparar den aktuella sidans innehåll i sessionen.

I funktionen editAction (där man hamnar efter ett tryck på ändra-knappen) anropar jag en funktion som hämtar ut den aktuella sidans innehåll och då kan jag använda *$this->views->add("me/$key",...* osv. för att först visa den aktuella sid-vyn (med eller utan byline) och därefter vyn med ändringsformuläret.

**Extra.** Jag gjorde båda extrauppgifterna: profilbild från Gravatar och dölja/visa en del av formuläret. Eftersom det skickas med en redirect-URL som "hidden value" i alla formulär (utom ändringsformuläret) kunde jag hänga på en "hash" (#comment) som används för att skrolla ned sidan automatiskt till ett ankare i formuläret. I ändringsformuläret används ingen redirect, så där kopierade jag in ett js-script som jag hittade på internet längst ned sidan. Är det sånt som kallas fulhack? :-)
 
Kmom03: Bygg ett eget tema
------------------------------------
 
**Klar med kmom03**. Det här kursmomentet var inte lika främmande som kursinledningen. Typografi, baslinjeraster och layout har jag sysslat mycket med. Mina erfarenheter rör i och för sig trycksaker och inte webbproduktion, men samma principer gäller, även om inte allt har samma benämningar.

Men för att börja från början läste jag de rekommenderade artiklarna, vilket var klart givande. Jag har gjort en del trycksaker för kunder och arbetat med typsnitt och baslinjeraster etc. i QuarkXpress och InDesign. I sådana program går det att låsa texten till baslinjerastret med en knapptryckning (nåja...). Att själv räkna på pixelstorlek och anpassa padding, marginaler, radavstånd etc. för att det ska stämma genom hela dokumentet med en viss typsnittsstorlek ställer lite andra krav.

**Problem**. Jag valde att använda **Semantic Grid** och att låna LESS-kod från Lydia-ramverket, precis som i övningen. Men i stället för 22 använde jag den magiska siffran 24 och ett radavstånd på 1,5em. På min testsida "Typografi" hade jag problem med att få raderna att linjera. Det var någon tiondels förskjutning som växte till en halv rad i slutet, plus att det fanns en liten avvikelse mellan kolumnerna. Jag modifierade bakgrundsbilden så att den var 24 pixlar hög och gjorde även en variant med tydligare horisontella linjer. För att enklare kunna granska ett par av testsidorna, gjorde jag så att baslinjerastret bara syns när muspekaren befinner sig i området.

För att få bukt med linjeringen fick jag ändra hårdkodade 22-pixelvärden till 24 på några ställen. I Lydia-variabel-filen fick jag ändra inställningarna för kolumnbredd och "gutter". Och självklart ändrade jag magicNumber-variabeln till 24 också. Men det var en hel del ytterligare småpill med padding och marginaler här och var innan jag fick till det så att jag var nöjd. Lärorikt dock!

Jag ville inte ha identiska bakgrunder på testsidorna, så i de olika routerna lade jag till *$app->theme->setVariable('wrapperclass', 'routenamn');* för att skicka med en klassvariabel till *anax-grid/index.tpl.php* där varje sida sedan kan få var sin bakgrund som styrs av klassen wrapperclass. Smidigt!

Jag har inte använt **CSS-ramverk** tidigare, bara lånat CSS-snuttar här och där på internet, men inser förstås att det finns massor att vinna på att använda ramverk. Exempelvis verkar LESS CSS-ramverket **Twitter Bootstrap** vara en guldgruva. 

**LESS** ger möjlighet att använda variabler, mixins, matematiska operationer och funktioner. Det utökar möjligheterna att skapa anpassade CSS-stilmallar ordentligt. Väldigt smidigt att vi fick använda style.php i det här momentet och slapp kompilera stilmallen själva för varje liten ändring. Tack vare LESS-variabler går det exempelvis (någorlunda) enkelt att anpassa baslinjerastret till typsnittsstorlek / radavstånd och att anpassa regionbredder till kolumnbredder etc. etc.

**lessphp**. Det verkar vettigt att låta lessphp kompilera filerna på serversidan. I den här övningen använde vi ju en minimerad version tillsammans med style.php, vilket var väldigt smidigt.

**Font Awesome** hade jag inte hört talas om, men den innehöll många ikoner som jag kände igen från olika webbplatser. Tack för det tipset, kommer säkert att använda detta framöver.  

**Normalize**. Ska man tro det man kan läsa om Normalize så är den bättre än en vanlig "CSS-reset", som jag för övrigt får erkänna att jag har varit dålig på att använda. Normalize resettar inte allt som i äldre reset-stilmallar. Syftet är ju alltså att överbryga olikheter mellan webbläsare, men Normalize siktar enbart in sig på sådant som verkligen behöver resettas (eller "normaliseras"). Verkar vettigt.

**Mitt tema**. Eftersom jag gjorde övningen från början till slut, utgick jag från den. Som sagt ovan ändrade jag till 24 som "magic number" och lade till olika bakgrunder på olika testsidor, för att underlätta granskningen. Bland annat därför att jag ville ha tydligare horisontella rader på sidorna med text, men på Font Awesome-sidan behövdes ingen bakgrund, så den fick bli vit. På testsidan Tema framgår det också att enbart regioner med innehåll visas. 



Kmom04: Databasdrivna modeller
------------------------------------
 
Fin redovisningstext om kursmoment 04.

Kmom05: Bygg ut ramverket
------------------------------------
 
Fin redovisningstext om kursmoment 05.

Kmom06: Verktyg och CI
------------------------------------
 
Fin redovisningstext om kursmoment 06.

Kmom07_10: Projekt och examination
------------------------------------
 
Fin redovisningstext om kursmoment 07_10.
