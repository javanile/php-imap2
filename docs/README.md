<div align="center">


<a href="https://ko-fi.com/francescobianco/goal?g=10">
<img src="https://raw.githubusercontent.com/javanile/php-imap2/refs/heads/main/docs/banner.svg" />
</a>


</div>

---

# 📚 php-imap2 Documentation Site

This folder contains the official **documentation website** for the [`php-imap2`](https://github.com/javanile/php-imap2) library, powered by **[Just the Docs](https://just-the-docs.github.io/just-the-docs/)**.

The site is built using **Jekyll** and is deployed via GitHub Pages.

---

## 📦 Contents

| File / Folder         | Description                                   |
|-----------------------|-----------------------------------------------|
| `index.md`            | Homepage of the documentation site            |
| `compatibility.md`    | Compatibility notes for PHP versions          |
| `examples.md`         | Usage examples and code snippets              |
| `functions.md`        | Detailed documentation of IMAP functions      |
| `google-playground.html` | (Experimental) IMAP function playground    |
| `banner.svg`          | Banner used in the site layout                |
| `php-imap2-banner-final.svg` | Final version of the banner logo      |
| `logo.png`            | Project logo shown in the documentation       |
| `_config.yml`         | Jekyll site configuration                     |
| `CNAME`               | Custom domain name for GitHub Pages           |

---

## 🚀 How to Run Locally

If you want to preview the documentation site locally, make sure you have [Ruby](https://www.ruby-lang.org/) and [Bundler](https://bundler.io/) installed, then run:

```bash
bundle install
bundle exec jekyll serve --livereload
```

Open [http://localhost:4000](http://localhost:4000) in your browser.

> 💡 This will reflect the exact look and structure of the live documentation site.

---

## 🌐 Custom Domain

This documentation site is published at:

```
https://php-imap2.javanile.org
```

The `CNAME` file ensures the GitHub Pages deployment uses the correct custom domain.

---

## 🛠 Contributing to the Docs

You're welcome to improve the docs! You can:

- Fix typos or outdated information
- Add new examples or function documentation
- Improve styling or navigation

Just open a pull request targeting the `docs/` folder or submit an issue.

---

## 📸 Preview

![php-imap2 banner](php-imap2-banner-final.svg)

---

## 📄 License

All documentation content is available under the same license as the main project (MIT).
