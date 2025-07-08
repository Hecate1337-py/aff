export async function onRequest(context) {
    const { request } = context;

    const links = [
        "https://s.shopee.co.id/1qQllnxDmK",
        "https://s.shopee.co.id/6VCbKOZPlI",
        "https://s.shopee.co.id/4AogY7N3zz",
        "https://s.shopee.co.id/9KWmhcKeKf",
        "https://s.shopee.co.id/8paW6hnILw",
        "https://s.shopee.co.id/6fW1WjunOS",
        "https://s.shopee.co.id/AUik5oOCy8",
        "https://s.shopee.co.id/9KWmhfoeGI",
        "https://s.shopee.co.id/7V58WJGOA6",
        "https://s.shopee.co.id/qYEa4Tbns",
        "https://s.shopee.co.id/6ppRj6SUUa",
        "https://s.shopee.co.id/4q4NLQw6Sm",
        "https://s.shopee.co.id/4q4NLQw6Sm",
        "https://s.shopee.co.id/3fsPxIrTkI",
        "https://s.shopee.co.id/4VRWwq1dwJ",
        "https://s.shopee.co.id/AA5thGiU8x",
        "https://s.shopee.co.id/BIXmvXuM4",
        "https://s.shopee.co.id/qYEa9nJRY",
        "https://s.shopee.co.id/3Aw9MUp6wK",
        "https://s.shopee.co.id/3qBq9j3xvk",
        "https://s.shopee.co.id/3LFZYoEo2F",
        "https://s.shopee.co.id/3LFZYoEo2F",
        "https://s.shopee.co.id/9ADMVXKmiw",
        "https://s.shopee.co.id/9ADMVXKmiw",
        "https://s.shopee.co.id/30cjAFAX3Y",
        "https://s.shopee.co.id/8UxfiM8ZGq",
        "https://s.shopee.co.id/3Aw9MYecvx",
        "https://s.shopee.co.id/z7akMVmc",
        "https://s.shopee.co.id/6VCbKhOvA1",
        "https://s.shopee.co.id/5pwuXUZmlu",
        "https://s.shopee.co.id/7fOYiro7LG",
        "https://s.shopee.co.id/4q4NLfkGlU",
        "https://s.shopee.co.id/9ADMVdrQB7",
        "https://s.shopee.co.id/8paW73VGUc",
        "https://s.shopee.co.id/30cjALIcuP",
        "https://s.shopee.co.id/3Aw9MeUR2B",
        "https://s.shopee.co.id/qYEaN04Zx",
        "https://s.shopee.co.id/30cjAM8sDK",
        "https://s.shopee.co.id/qYEaNUjWD",
        "https://s.shopee.co.id/20kByWkqYa",
        "https://s.shopee.co.id/AUik69Ondy",
        "https://s.shopee.co.id/5VK48y2Jvt",
        "https://s.shopee.co.id/2B3cAqTRlQ",
        "https://s.shopee.co.id/5px1vqYzka",
        "https://s.shopee.co.id/9KWu6I3Sge",
        "https://s.shopee.co.id/3AwGkxyy02",
        "https://s.shopee.co.id/50NuwLSu67",
        "https://s.shopee.co.id/6fW8vPdkGr",
        "https://s.shopee.co.id/50NuwLw7Xe",
        "https://s.shopee.co.id/8Uxn6n1hYI",
        "https://s.shopee.co.id/5VKBXHdNoJ",
        "https://s.shopee.co.id/3VZ79c1QXa",
        "https://s.shopee.co.id/9KWu6LBKAQ",
        "https://s.shopee.co.id/AKPRIBNoHc",
        "https://s.shopee.co.id/AUirUUaNIP",
        "https://s.shopee.co.id/4VReLTFz3Q",
        "https://s.shopee.co.id/8padVRHDuw"
    ];

    if (links.length === 0) {
        return new Response("No links available.", { status: 500 });
    }

    const selectedLink = links[Math.floor(Math.random() * links.length)];

    // Log ke console
    const ip = request.headers.get("CF-Connecting-IP") || "unknown";
    const userAgent = request.headers.get("User-Agent") || "unknown";
    const time = new Date().toISOString();
    console.log(`[${time}] ${ip} - ${selectedLink} - ${userAgent}`);

    // Redirect
    return Response.redirect(selectedLink, 302);
}
