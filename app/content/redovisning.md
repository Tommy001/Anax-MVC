<a name="kmom01"></a> 
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

<a name="kmom02"></a> 
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

<a name="kmom03"></a> 
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


<a name="kmom04"></a> 
Kmom04: Databasdrivna modeller
------------------------------------
 
**Klar med kmom04.** Det här momentet tog lång tid och jag känner nog att det var det svåraste hittills i hela kurspaketet. Man skulle dels skapa en användarfunktion med både CForm och CDatabase och dels bygga om kommentarsfunktionen från kmom02 så att den sparar data i databasen i stället för sessionen.

Precis som i kmom02 lät jag kommentarsfunktionen ha separata flöden på de olika sidorna. Kommentarer kan läggas in på Start, Redovisning och Tärningsspel.

Det var en stor textmassa att ta sig igenom före övningarna. När man som jag inte har tillräckliga ramverkskunskaper för att enkelt hänga med i texterna, blir det väldigt svårt att förstå mer än de stora dragen. Men jag läste allt och tog till mig det jag kunde.

Jag tyckte att jag fick till en ganska tydlig **användarsida**, där det framgår klart vilka användare som har "slängts" i papperskorgen, vilka som är aktiva och vilka som inte är det. I menyn kan man välja att se enbart aktiva, inaktiva etc. samt återställa databasen. På sidorna har jag använt mig av baslinjerastret från förra momentet, så raderna i användartabellen och papperskorgen linjerar faktiskt. Från förra momentet har jag också lånat några Font Awesome-symboler.

Nåväl... jag hade lite problem med att använda custom-valideringen i **formulärhanteringen**. Försökte få till en kontroll av att användarnamnet inte redan fanns i databasen, eftersom det fältet skulle vara unikt. Och det lyckades till slut, tack vare hjälp från chatt och forum och massa letande i CFormElement. Se den här [länken](http://dbwebb.se/forum/viewtopic.php?f=40&t=3769) om det problemet.

Det är praktiskt med alla "automatiska" kontroller och valideringar som kan göras i formulären bara genom att ange t.ex. "required" eller "not_empty". Jag ägnade rätt mycket tid till att försöka förstå koden i CForm eller snarare CFormElement. Jag lyckades stänga av fieldset (som jag tycker är fult) och förstod att det går att styla elementen med CSS-klasser (ja, eller LESS-klasser), vilket jag gjorde i kommentarsformuläret för att få fälten till den bredd jag ville ha. Försökte få fälten namn, mail och webbsida på samma rad efter varandra, men lyckades inte med det. Är detta kanske en svaghet, att man blir rätt så begränsad när det gäller stylingen av formulärelementen? Eller så är det jag som inte kunde bättre.

**Vägval**. För att implementera kommentarer med databasstöd utgick jag från den gamla *CommentController* i Vendor-mappen och lade den nya klassen i app/src/Comment och gjorde sedan en extra klass *Comments* som "extendade" CDatabaseModel. Sedan använde jag *Comments* för att skapa/spara/radera kommentarer inifrån *CommentController*. Den här uppgiften tog minst lika mycket tid som uppgiften att hantera användare, så jag försökte mig inte på några extravaganser.

Jag provade att bygga SQL-queries automatiskt med hjälp av query-byggaren, inget avancerat, men jag förstår att det kan vara en bra hjälp, se t.ex. rad 31-37 i *app/src/Comment/Comments.php*. Just där pratar jag direkt med CDataBase, borde kanske ha gjort en hjälpmetod i CDataBaseModel i stället. Jag provade lite olika metoder för att se vad som känns bäst. 

**Extra.** På grund av tidsbrist försökte jag mig inte på extrauppgiften.
Däremot har jag snyggat upp me-sidan, valt en annan annan typografi, lagt in undermenyer och "flyttat hem" Tema-momentet från förra uppgiften så att man slipper växla mellan två frontkontroller. Ska lägga till en sidmeny på redovisningssidan också så att man slipper skrolla upp och ned, men det blir i nästa kursmoment.

Måste också tacka alla på chatten som hjälper till både på dagtid, helger och kvällar... thebiffman, Bobbzorsen, foiki, Sylvanas, Olund osv. Utan dem hade det här tagit betydligt längre tid.

<a name="kmom05"></a>
Kmom05: Bygg ut ramverket
------------------------------------
 
**Klar med kmom05.** Det var skönt att läsa inledningen till det här kursmomentet, eftersom kmom04 var väldigt krävande. Det stod "Jag tänkte faktiskt att vi tar det lite lugnare nu"... Vad skönt att höra :-).

För min egen del blev det inte fullt så enkelt dock. Jag bestämde mig för anpassa en gammal kodsnutt för uppladdning av bilder som jag hade liggande. Uppladdning är något som jag har saknat i Anax-MVC, så det kunde ju passa bra. Jag har ingen stor tidigare programmeringserfarenhet och den här koden hör till det första jag jag gjorde i PHP för ett par år sedan. Det fanns med andra ord utrymme för förbättring, så jag tog tillfället i akt att skriva om koden. Eftersom jag har tänkt stoppa in lite fler funktioner längre fram fick den helt ospecifikt heta **mymodule**. Enkelt och bra.

**Problem.** Jag krånglade till det genom att göra färdigt modulen i min befintliga me-sida, där jag bland annat tog hjälp av CDatabase och använde befintlig formatering från kmom03 etc. Tänkte att det skulle vara enklare för den som rättar att funktionen låg integrerad på me-sidan.

Sedan körde jag fast ordentligt när jag skulle testa modulen i en ren Anax-MVC, som jag klonade ner. Ingenting fungerade. Det blev till att sätta sig att läsa instruktioner i kmom01 igen och plåga folk på chatten med frågor. Fick till exempel för mig att det hade varit bra om modulen kunde köras direkt i vendor-mappen, utan att man behöver flytta filer hit och dit. Det lyckades jag inte reda ut. Jag övergav det spåret och övergav också tanken på att låta modulen vara beroende av CDatabase. Det handlade ju bara om en liten testtabell i MySQL.

**Lärdomar.** Jag lärde mig en del nytt, som t.ex. att det inte går att använda globala funktioner hur som helst inne i en namespace, som t.ex. "new PDO()...." som i stället måste skrivas "new \PDO()...".

Jag har också lärt mig att göra en try/catch-metod och att använda Exception-hantering, vilket jag annars har lyckats undvika.

**Avslutningsvis.** Tlll slut fick jag ändå allt att fungera och såvitt jag förstår var det här momentet mer en övning i att publicera egen kod på Github och Packagist, än att leverera en fantastiskt snillrik kod (även om jag inte alls är missnöjd med mitt bidrag, tvärtom). Jag har nu testat att det går att använda Composer för att ladda hem modulen till en ren Anax-MVC och testat att mina instruktioner går att följa.

Jag har sett till att Packagist uppdateras automatiskt när jag pushar till Github och har skrivit en Readme-fil och angivit MIT-licens etc. Se mitt Github-repo [här](https://github.com/Tommy001/mymodule) och mitt Packagist-konto här [här](https://packagist.org/packages/tommy001/mymodule).

**Extra.** På grund av tidsbrist blev det inget extra den här gången heller. Jag prioriterar att hålla tempot i kursen.

<a name="kmom06"></a>
Kmom06: Verktyg och CI
------------------------------------
 
**Klar med kmom06.** Just i skrivande stund känner jag mig ganska nöjd med hur jag har genomfört det här momentet. Men det var synd att jag inte läste vad som skulle komma i kmom06, när jag valde modul i kmom05. Hade jag velat göra det enkelt för mig kunde jag ha valt att göra en modul som var enklare att testa än just bilduppladdning. Jag har nu spenderat många timmar med att försöka komma runt problemet att vissa saker helt enkelt är svårare att testa med phpunit. Men å andra sidan har jag förstås lärt mig mer än vad jag annars skulle ha gjort. Dessutom lät jag min modul vara beroende av Anax-MVC för att fungera. Det gjorde inte heller saken enklare.

**Problem och lärdomar.** Efter uppladdning av en bild finns alla fildata i den "superglobala" variabeln $_FILES. Redan här har vi två problem. Det första är att Scrutinizer ger minuspoäng: "movefile uses the super-global variable $_FILES which is generally not recommended." Bara att acceptera. Det andra är att det normalt inte går att använda phpunit för att testa metoder som använder sig av move_uploaded_file och is_uploaded_file, eftersom det skulle kräva en riktig uppladdning via HTTP POST. Vilket inte var så enkelt att komma förbi.

Lösningen var att använda en Setup-metod i testfilen där jag fyllde variabeln $_FILES med "normala värden som name, tmp_name, size, type etc. Sedan skapade jag två egna fuskmetoder med samma namn (move_uploaded_file och is_uploaded_file) som gjorde det förväntade jobbet med hjälp av is_file() och copy_file() i stället. Normalt skulle man fått felmeddelanden om att dessa funktioner inte kan omdeklareras, men om alltihop sker under samma namespace, så kommer man faktiskt undan med detta. Det krävdes dock att även config-filen där jag lade mina fusk-funktioner fick samma namespace. Titta gärna lite närmare på [config-filen](https://github.com/Tommy001/mymodule/blob/master/test/config.php).

För att överhuvudtaget kunna testa min klass UploadController från kmom05, var jag dessutom tvungen att skriva om den så att det blev många mindre metoder i stället för några få stora. Just detta jobb lärde jag mig mycket på. Det är förstås så man ska göra. Dela upp stora problem i många små.

För att kunna testa de flesta kodraderna i metoderna, fick man simulera vad som händer när det går fel (någon laddar upp en icke godkänd bild) också. Inte bara när allt går som det ska. Testfilen blev med andra ganska lång till slut.

**Mindre problem.** Jag fick inte metoden som "sanerar" filnamn med otillåtna tecken att validera i mitt testfall. Efter mycket felsökning visade det sig att php-funktionen strtolower inte kan hantera multibyte-tecken som å, ä och ö, utan fick bytas ut mot mb_strtolower (i vilken UTF-8 måste deklareras). Se där, en förbättring som jag inte hade sett utan phpunit.

Jag tillbringade en hel del tid med att läsa i phpunit-manualen, bland annat för att se hur jag kunde exkludera alla mappar utom src-mappen. Det visade sig vara ganska enkelt gjort med en XML-fil som jag lade bredvid config.filen.

Jag var inte **bekant** med någonting av teknikerna i kmom06 sedan tidigare. Men när jag väl kommit förbi alla problem jag nämner ovan, så var det ganska enkelt att skriva testfallen.

**Travis.** När jag trodde att jag var klar med kursmomentet för den här gången fick jag klart för mig att vendor-mappen inte ska vara med på github. Eventuella beroenden skulle ju laddas med composer, vilket sedan inte fungerade på grund av att jag hade utelämnat raden "- composer install --no-dev" i filen travis-yml. Fick hjälp via chatten, så det löste sig till ganska omgående. 

**Scrutinizer.** Jag skapade ett konto på Scrutinizer och fick allt att fungera innan jag började på kmom06, bara för att det verkade vara intressant. Så det var ju en liten överraskning att en av uppgifterna var just detta. Det här ett mycket bra verktyg. Jag sitter ju ensam på mitt kontor och kodar och har ingen som hänger över axeln och skriker "dålig kod" om jag skulle slinta med tangenterna. Så bra då att Scrutinizer finns. Jag har lärt mig mycket redan. Dock måste man komma ihåg att kontrollera script-delen i travis.yml så att inte "coverage html report" är med, när man pushar till Github och därmed också till Scrutinizer. Det tog mig nästan en hel söndag att hitta det felet. Resultatet blir att inspektionen misslyckas.

Jag kommer definitivt att fortsätta använda Scrutinizer och jag kommer helt klart att skriva kod med tanke på den ska gå att testa i fortsättningen. Sedan om jag faktiskt kommer att genomföra testningen varje gång, är en annan femma.

**Extra**. Nix, ingen extrauppgift.


Kmom07_10: Projekt och examination
------------------------------------
 
**Äntligen klar med projektet.** Innan jag börjar med redovisningen av kraven ska jag kanske nämna att jag använde mig av css-ramverket (eller snarare less-ramverket) Bootstrap 3. Jag fick besked via chatten att det var OK att göra så. Jag laddade hem hela källkodspaketet och började kompilera med Grunt lokalt. Jag insåg att det var bättre att hålla det enkelt och försökte därför inte implementera någon less-kompilering på studentservern. Till denna server (och Github) laddade jag helt enkelt bara upp den resulterande bootstrap.css-filen tillsammans med några kompletterande css-filer. 

Webbplatsen är därmed "mobile-first" och har flera brytpunkter (prova att minska bredden rejält) och fungerar bra på alla skärmstorlekar.

###Krav 1 ,2 och 3
Jag valde att göra ett forum för båtnördar som jag kallar för **"Navigare Necesse Est"**. Fokus ligger alltså på *"Frågor & Svar om allt som rör båtar"*. 

Webbplatsens **inloggning** sker på en separat sida, eftersom jag behövde plats i headern till ett sökfält (annars tycker jag det är praktiskt med permanenta inloggningsfält i headern). 

Det går att lägga till nya användare och den person som är inloggad kan ändra sin egen profil, ingen annans. Om det inte är administratören som är inloggad förstås (admin/admin), han/hon kan ändra det mesta på webbplatsen. Varje användare symboliseras med en **gravatar** och användarnamnet. På de flesta ställen är också gravataren klickbar, för att man enkelt ska kunna se användarens profil.

De erfordrade sidorna finns med: **Start**, **Frågor**, Taggar (eller **Ämnen** som jag kallar dem) och en **Om oss**-sida.

Utan att vara inloggad kan vem som helst se de mesta på hela webbplatsen, söka efter frågor och läsa svar och kommentarer. Däremot måste man skapa en profil och vara inloggad för att kunna ställa egna frågor, svara eller kommentera på andras frågor, vilket förstås görs i markdown.

På sidan som visar användarprofilen syns även de eventuella frågor som användaren har ställt och vilka frågor som han/hon har svarat på. Klickar man på frågans titel visas frågan.

**Startsidan** visar de tre senaste inlagda frågorna, i en förkortad version. Genom att klicka på en fråga får man se hela frågan, tillsammans med eventuella svar och kommentarer. De fem populäraste taggarna (ämnena) och de fyra aktivaste användarna är också klickbara. Hade en hel del huvudbry att väga samman frågor, svar och kommentarer, för att få fram en lista med de aktivaste användarna, men det gick till slut. Jag satte ihop en vy i MySQL genom att slå ihop tre SELECT-satser med UNION ALL. Genom att sedan använda COUNT(*) på en kolumn i den resulterande vyn kunde jag räkna det totala antalet inlägg.

De tillgängliga **ämnena** eller "taggarna" visas på en egen sida. Klickar man på ett ämne visas alla frågor som är kopplade till den taggen. På sidan med frågor framgår också vilka taggar som frågeställaren har valt för sin fråga.

På inrådan från mos valde jag att inkludera Anax-MVC som en modul i vendormappen med hjälp av composer, som vilken annan modul som helst. Genom att exkludera vendormappen i .gitignore var det en smal sak att ladda upp projektet på Github. Allt ligger ju dessutom redan på rätt ställen. Därför blev det enkelt att skriva anvisningar i README-filen också. Den som laddar ner "Navigare" behöver bara köra composer med befintlig composer.json-fil. Det krävs förstås lite trix med att initiera databastabellerna också, men där finns två möjligheter. Efter att ha konfigurerat databasparametrarna i en särskild config-fil kan man bara lägga till "/setup" efter startsidans URL. Det finns även en separat SQL-fil som kan importeras direkt med exempelvis phpMyAdmin.

Webbplatsen ligger också på studentservern med ett exempelinnehåll.

###Krav 4
Ett svar kan markeras som rätt svar av den som har ställt frågan. Det går att rösta +1 eller -1 på varje fråga/svar/kommentar. Begränsningen är att man inte kan rösta på ett eget inlägg och man kan bara rösta en gång på samma inlägg. Det krävde också lite funderande... men var enkelt löst med hjälp av en separat tabell där jag samlar all röstning.

###Krav 5

Jag införde ett poängsystem där jag väger samman alla gjorda inlägg och betygen som dessa inlägg har fått från andra användare. Se kommentaren i UsersController på rad 150 för att se hur regeln hänger ihop.

Poängen (eller "ryktet") visas på användarprofilens sida (**Meritpoäng** uppe till höger).

###Krav 6
Jag hade stora planer på att testa koden med phpunit redan från början, men tiden rann iväg och jag har tyvärr fått slänga ut allt som hade med phpunit att göra. En vecka in på nåja-perioden inser jag att jag måste dra ett streck här.

Men jag kan berätta om vad jag gjort i övrigt som inte framgår av kraven.

Sidan har en sökfunktion där man bara behöver skriva några bokstäver för att få träff (utan wildcards).

Det finns kontroller som  gör att administratören inte kan slänga en fråga i papperskorgen, om det finns ett svar eller en kommentar kopplad till frågan. Samma sak med svaren, finns det en kommentar går det inte att slänga svaret. Om administratören däremot slänger inläggen i rätt ordning (kommentarer först osv.) så kan svar eller frågor slängas i papperskorgen. Om dessa dessutom tas bort definitivt från papperskorgen, raderas även motsvarande inlägg permanent i tabellerna kommentarer och svar. Även användare kan tas bort på motsvarande sätt. En användare som gjort ett inlägg kan alltså inte tas bort.

Det finns också kontroller av vem som har röstat, så att samma användare inte kan rösta på samma inägg mer än en gång.

För att kunna markera vald meny i headern med hjälp av CSS även på undersidor gjorde jag en funktion som dynamiskt ändrade ID på body-taggen. Se funktionen *SetBodyID()* på rad 45 i *Navigare/functions.php*. Den anropas från rad 15 i *Navigare/theme/index.tpl.php*. 

Jag har kopplat projektet till Scrutinizer och fick betyget 7,43. Hade nog lite för långa metoder på ett par ställen, särskilt i UsersController. 

###Problem & Lösningar
Jag hade en del problem med CForm, som (efter tips från mos) visade sig inte gilla fler än ett postat formulär per sida. Postade argument som skickas till metoden uppfattar CForm som att formuläret har postats och då blir det förstås felmeddelanden pga valideringen (tomma fält etc.) direkt när sidan öppnades. Det innebar att jag fick göra om ett antal knappar så att de inte använder POST, för att skicka argument till metoden som hanterar formuläret. Ett annat problem med CForm var att det några gånger i veckan, helt sporadiskt, gav upphov till ett "Notice-meddelande" som inleddes med "( ! ) Notice: Indirect modification of overloaded element of Mos\HTMLForm\CForm..:". Det har inte hänt sedan jag laddade upp projektet på studentservern, så det kanske bara händer lokalt.

Jag hade problem att styla CForm som jag ville, särskilt när det gäller "multiple-checkbox", så där tog jag mig friheten att lägga till lite kod i CFormElement.php (se README-filen på Github.)

En annan sak med checkboxarna var att den interna custom-valideringen gjorde att varje enskild kryssruta visade felmeddelandet. Därför fick jag göra en separat validering, utanför CForm. Försöker man t.ex. välja fler än tre taggar, så visas en (1) varningstext, inte lika många som det finns kryssrutor i formuläret.

Det som annars tog mest tid var nog komplexiteten. Minsta ändring på ett ställe fick konsekvenser på andra ställen. Om samma databas-query gick att använda till flera olika funktioner, kunde en ändring göra att något annat slutade att fungera. På några ställen valde jag till slut att vara mindre DRY och faktiskt upprepa kodsnuttar som liknar varandra, för att det var enklare att hålla isär flödena på det sättet. DRY är inte alltid en fördel. Ibland blir det svårläst och kan leda till fel som är svåra att hitta.

###Kursutvärdering
Att jag, utan några som helst förkunskaper om MVC-ramverk har lyckats komma i mål med det här projektet, är ju ett bra betyg för kursen som sådan. Handledningen via chatt och forum är dock helt nödvändig för att det ska fungera. Mitt problem har varit att eftersom jag jobbar heltid, så är det kvällar och helger som jag mest jobbat med kursen. Men då går det ju inte att begära att t.ex. chatten ska vara bemannad. Men ofta har den varit det ändå! Andra studenter eller hjälpande händer har för det mesta funnits inom räckhåll.

Jag har ibland tänkt att instruktionerna för de olika kursmomenten har varit lite luddiga, men inser också att de som läser kursen kommer från vitt skilda håll. Många av mina medstudenter har redan gedigna programmeringskunskaper och kursinstruktionerna är ju riktade till samtliga. Forum och chatt väger effektivt upp sådant som inte enkelt går att förstå i instruktionerna, när förkunskaperna inte räcker till.

Jag ger kursen en sjua av tio möjliga.
