import puppeteer from "puppeteer";

async function screenshot(url) {
    try {
        
        const browser = await puppeteer.launch({
            headless: true,
            args: ["--no-sandbox", "--disable-setuid-sandbox"],
        });
        const page = await browser.newPage();
        await page.goto(process.argv[2], {
            waitUntil: "networkidle2",
        });
        let screen = await page.screenshot({
            fullPage: true,
        });
        
        process.stdout.write(screen);
        await browser.close();
        return 0;
    } catch (error) {
        process.stderr.write(error);
        return 1;
    }
}

if (process.argv.length < 3) {
    console.error("Usage: node screen.js <url>");
    process.exit(1);
}
process.exit(await screenshot(process.argv[2]));

