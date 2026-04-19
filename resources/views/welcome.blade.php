<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SheetsGPT — AI-Powered Spreadsheet Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,300;12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Epilogue:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap"
        rel="stylesheet" />

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <style>
        :root {
            --bg: #08090e;
            --card: rgba(255, 255, 255, 0.028);
            --blue: #3b9eff;
            --cyan: #00e8c6;
            --purple: #8b5cf6;
            --txt: #eef1f8;
            --txt2: rgba(238, 241, 248, 0.52);
            --txt3: rgba(238, 241, 248, 0.28);
            --border: rgba(255, 255, 255, 0.065);
            --borderglow: rgba(59, 158, 255, 0.22);
            --FD: 'Bricolage Grotesque', sans-serif;
            --FB: 'Epilogue', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden
        }

        body {
            background: var(--bg);
            color: var(--txt);
            font-family: var(--FB);
            overflow-x: hidden;
            cursor: none
        }

        /* CURSOR */
        #cur {
            position: fixed;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--blue);
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%)
        }

        #cur-ring {
            position: fixed;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid rgba(59, 158, 255, 0.35);
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            transition: width .25s, height .25s, border-color .25s
        }

        body:has(a:hover) #cur-ring,
        body:has(button:hover) #cur-ring {
            width: 48px;
            height: 48px;
            border-color: rgba(59, 158, 255, 0.6)
        }

        #cur-glow {
            position: fixed;
            width: 560px;
            height: 560px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 158, 255, 0.055) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
            transform: translate(-50%, -50%);
            transition: opacity .3s
        }

        /* BG */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image: linear-gradient(rgba(59, 158, 255, .02) 1px, transparent 1px), linear-gradient(90deg, rgba(59, 158, 255, .02) 1px, transparent 1px);
            background-size: 58px 58px
        }

        /* LOADER */
        #loader {
            position: fixed;
            inset: 0;
            z-index: 9990;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .5s, visibility .5s
        }

        #loader.out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none
        }

        .ld-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px
        }

        .ld-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: linear-gradient(135deg, #1a6fd4, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            animation: ld-pulse 1.1s ease-in-out infinite
        }

        @keyframes ld-pulse {

            0%,
            100% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.09)
            }
        }

        .ld-name {
            font-family: var(--FD);
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -.03em
        }

        .ld-bar {
            width: 150px;
            height: 1.5px;
            background: rgba(255, 255, 255, .08);
            border-radius: 2px;
            overflow: hidden
        }

        .ld-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b9eff, #8b5cf6);
            animation: ld-run 1.1s cubic-bezier(.4, 0, .2, 1) forwards;
            width: 0
        }

        @keyframes ld-run {
            to {
                width: 100%
            }
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0;
            inset-inline: 0;
            z-index: 100;
            height: 64px;
            padding: 0 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(8, 9, 14, .75);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            transition: background .3s
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: var(--FD);
            font-size: 1.18rem;
            font-weight: 700;
            color: var(--txt);
            text-decoration: none;
            letter-spacing: -.025em
        }

        .logo-icon {
            width: 29px;
            height: 29px;
            border-radius: 7px;
            background: linear-gradient(135deg, #1a6fd4, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px
        }

        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none
        }

        .nav-links a {
            font-size: .87rem;
            color: var(--txt2);
            text-decoration: none;
            transition: color .2s;
            letter-spacing: .01em
        }

        .nav-links a:hover {
            color: var(--txt)
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center
        }

        .btn-ghost {
            padding: 8px 17px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--txt2);
            border-radius: 8px;
            font-family: var(--FB);
            font-size: .84rem;
            cursor: none;
            transition: all .2s
        }

        .btn-ghost:hover {
            border-color: var(--borderglow);
            color: var(--txt);
            background: rgba(59, 158, 255, .04)
        }

        .btn-nav {
            padding: 8px 19px;
            border: none;
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            color: #fff;
            border-radius: 8px;
            font-family: var(--FB);
            font-size: .84rem;
            font-weight: 500;
            cursor: none;
            transition: opacity .2s, transform .15s
        }

        .btn-nav:hover {
            opacity: .86;
            transform: translateY(-1px)
        }

        /* HERO */
        #hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 96px 5% 60px;
            overflow: hidden
        }

        .orb1 {
            position: absolute;
            top: 8%;
            left: 16%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(26, 111, 212, .1) 0%, transparent 68%);
            animation: odrift 14s ease-in-out infinite
        }

        .orb2 {
            position: absolute;
            bottom: 6%;
            right: 4%;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(139, 92, 246, .09) 0%, transparent 68%);
            animation: odrift 18s ease-in-out infinite reverse
        }

        @keyframes odrift {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-26px)
            }
        }

        .hero-inner {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            gap: 68px;
            align-items: center
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 14px;
            border: 1px solid rgba(59, 158, 255, .18);
            background: rgba(59, 158, 255, .055);
            border-radius: 100px;
            margin-bottom: 24px;
            font-size: .72rem;
            font-weight: 500;
            color: var(--blue);
            letter-spacing: .06em;
            text-transform: uppercase
        }

        .bdot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--blue);
            animation: blink 2.2s ease-in-out infinite
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        h1.htitle {
            font-family: var(--FD);
            font-size: clamp(2.65rem, 4.1vw, 3.95rem);
            font-weight: 800;
            line-height: 1.07;
            letter-spacing: -.035em;
            margin-bottom: 20px
        }

        .gtxt {
            background: linear-gradient(115deg, #3b9eff 0%, #a78bfa 52%, #00e8c6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200%;
            animation: gmove 5.5s ease-in-out infinite
        }

        @keyframes gmove {

            0%,
            100% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }
        }

        .hero-sub {
            font-size: 1.05rem;
            font-weight: 300;
            color: var(--txt2);
            line-height: 1.76;
            max-width: 455px;
            margin-bottom: 38px
        }

        .hero-ctas {
            display: flex;
            gap: 11px;
            flex-wrap: wrap;
            margin-bottom: 50px
        }

        .btn-xl {
            padding: 13px 28px;
            border-radius: 10px;
            font-family: var(--FB);
            font-size: .94rem;
            font-weight: 500;
            cursor: none;
            transition: all .25s;
            position: relative;
            overflow: hidden;
            letter-spacing: .01em
        }

        .btn-xl-p {
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            border: none;
            color: #fff
        }

        .btn-xl-p::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #3b9eff, #a78bfa);
            opacity: 0;
            transition: opacity .3s
        }

        .btn-xl-p:hover::after {
            opacity: 1
        }

        .btn-xl-p>span {
            position: relative;
            z-index: 1
        }

        .btn-xl-g {
            background: transparent;
            border: 1px solid var(--borderglow);
            color: var(--txt);
            display: flex;
            align-items: center;
            gap: 8px
        }

        .btn-xl-g:hover {
            background: rgba(59, 158, 255, .05);
            border-color: var(--blue)
        }

        .play-i {
            width: 21px;
            height: 21px;
            border-radius: 50%;
            border: 1.5px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .54rem;
            flex-shrink: 0
        }

        .hero-stats {
            display: flex;
            gap: 34px;
            padding-top: 34px;
            border-top: 1px solid var(--border)
        }

        .snum {
            font-family: var(--FD);
            font-size: 1.78rem;
            font-weight: 700;
            letter-spacing: -.03em
        }

        .slbl {
            font-size: .76rem;
            color: var(--txt3);
            margin-top: 3px;
            letter-spacing: .03em
        }

        /* HERO CHAT MOCKUP */
        .hero-visual {
            position: relative;
            height: 555px;
            transition: transform .12s ease-out;
            transform-style: preserve-3d
        }

        .chat-win {
            position: absolute;
            inset: 0;
            background: rgba(11, 15, 27, .88);
            backdrop-filter: blur(22px);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, .52), 0 0 0 1px rgba(255, 255, 255, .035), inset 0 1px 0 rgba(255, 255, 255, .045);
            display: grid;
            grid-template-columns: 188px 1fr
        }

        /* sidebar */
        .cw-sb {
            border-right: 1px solid var(--border);
            background: rgba(0, 0, 0, .18);
            padding: 18px 13px;
            display: flex;
            flex-direction: column;
            gap: 12px
        }

        .cw-wslbl {
            font-family: var(--FD);
            font-size: .8rem;
            font-weight: 600;
            color: var(--txt);
            letter-spacing: -.01em;
            margin-bottom: 4px
        }

        .cw-sec {
            font-size: .63rem;
            color: var(--txt3);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 8px
        }

        .cw-chip {
            background: rgba(59, 158, 255, .06);
            border: 1px solid rgba(59, 158, 255, .13);
            border-radius: 7px;
            padding: 7px 10px;
            font-size: .71rem;
            color: var(--txt2);
            display: flex;
            align-items: center;
            gap: 7px
        }

        .cw-chip-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan);
            flex-shrink: 0
        }

        .cw-div {
            height: 1px;
            background: var(--border);
            margin: 2px 0
        }

        .cw-upload {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 7px
        }

        .cw-choose {
            padding: 7px 10px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: rgba(255, 255, 255, .03);
            font-size: .71rem;
            color: var(--txt2);
            text-align: center;
            cursor: default
        }

        .cw-upl-btn {
            padding: 8px 10px;
            border-radius: 7px;
            background: rgba(59, 158, 255, .1);
            border: 1px solid rgba(59, 158, 255, .18);
            font-size: .71rem;
            font-weight: 500;
            color: var(--blue);
            text-align: center;
            cursor: default
        }

        /* chat area */
        .cw-chat {
            display: flex;
            flex-direction: column;
            overflow: hidden
        }

        .cw-topbar {
            padding: 11px 15px;
            border-bottom: 1px solid var(--border);
            background: rgba(0, 0, 0, .12);
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .cw-topbar-title {
            font-family: var(--FD);
            font-size: .79rem;
            font-weight: 600;
            letter-spacing: -.01em
        }

        .cw-back {
            font-size: .71rem;
            color: var(--txt3)
        }

        .cw-msgs {
            flex: 1;
            overflow-y: auto;
            padding: 13px 13px 8px;
            display: flex;
            flex-direction: column;
            gap: 11px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .06) transparent
        }

        /* table bubble */
        .msg-tbl-wrap {
            background: rgba(11, 18, 34, .7);
            border: 1px solid var(--border);
            border-radius: 11px;
            overflow: hidden;
            overflow-x: auto
        }

        .msg-tbl {
            min-width: 360px;
            border-collapse: collapse;
            font-size: .68rem
        }

        .msg-tbl th {
            background: rgba(0, 0, 0, .22);
            padding: 7px 10px;
            text-align: left;
            color: var(--txt3);
            font-size: .63rem;
            font-weight: 500;
            letter-spacing: .04em;
            border-bottom: 1px solid var(--border);
            white-space: nowrap
        }

        .msg-tbl td {
            padding: 6px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, .03);
            color: var(--txt2);
            vertical-align: top
        }

        .msg-tbl tr:last-child td {
            border-bottom: none
        }

        .td-mono {
            font-family: monospace;
            font-size: .61rem;
            color: var(--blue)
        }

        .exp-row {
            padding: 6px 11px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end
        }

        .exp-btn {
            font-size: .63rem;
            color: var(--txt3);
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: default
        }

        /* bubbles */
        .msg-u {
            align-self: flex-end;
            background: rgba(255, 255, 255, .068);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 13px 13px 3px 13px;
            padding: 8px 13px;
            max-width: 78%;
            font-size: .74rem;
            color: var(--txt)
        }

        .msg-a {
            background: rgba(11, 18, 34, .68);
            border: 1px solid var(--border);
            border-radius: 3px 13px 13px 13px;
            padding: 10px 13px;
            max-width: 90%;
            font-size: .74rem;
            color: var(--txt2);
            line-height: 1.6
        }

        .msg-a-link {
            color: var(--blue);
            text-decoration: underline
        }

        .msg-a-inner-tbl {
            background: rgba(0, 0, 0, .22);
            border: 1px solid var(--border);
            border-radius: 7px;
            overflow: hidden;
            margin-top: 8px
        }

        .msg-a-inner-tbl table {
            width: 100%;
            border-collapse: collapse;
            font-size: .67rem
        }

        .msg-a-inner-tbl th {
            padding: 5px 9px;
            background: rgba(0, 0, 0, .18);
            border-bottom: 1px solid var(--border);
            color: var(--txt3);
            font-size: .6rem;
            text-align: left;
            font-weight: 500
        }

        .msg-a-inner-tbl td {
            padding: 6px 9px;
            color: var(--txt2)
        }

        /* input */
        .cw-input-bar {
            padding: 9px 11px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(0, 0, 0, .13)
        }

        .cw-inp {
            flex: 1;
            background: rgba(255, 255, 255, .035);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 7px 11px;
            font-size: .72rem;
            color: var(--txt3);
            font-family: var(--FB)
        }

        .cw-send {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            border: none;
            color: #fff;
            font-size: .72rem;
            cursor: default;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        /* floating chips */
        .fchip {
            position: absolute;
            background: rgba(11, 15, 27, .92);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 12px;
            padding: 10px 13px;
            box-shadow: 0 12px 38px rgba(0, 0, 0, .34);
            animation: fc-float 5s ease-in-out infinite
        }

        .fchip:nth-child(2) {
            animation-delay: -2s;
            animation-duration: 6s
        }

        .fchip:nth-child(3) {
            animation-delay: -3.4s;
            animation-duration: 4.6s
        }

        @keyframes fc-float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        .fc-ai {
            bottom: 52px;
            left: -70px;
            width: 170px
        }

        .fc-lbl {
            font-size: .61rem;
            color: var(--txt3);
            margin-bottom: 4px;
            letter-spacing: .04em
        }

        .fc-txt {
            font-size: .7rem;
            color: var(--txt2);
            line-height: 1.5
        }

        .fc-txt strong {
            color: var(--cyan)
        }

        .fc-acc {
            top: 44px;
            right: -60px;
            width: 144px
        }

        .fc-num {
            font-family: var(--FD);
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: -.03em
        }

        .fc-sub {
            font-size: .63rem;
            color: var(--txt3);
            margin-top: 2px
        }

        .fc-bar {
            margin-top: 7px;
            height: 1.5px;
            background: rgba(255, 255, 255, .07);
            border-radius: 1px;
            overflow: hidden
        }

        .fc-bar-fill {
            height: 100%;
            width: 96%;
            background: linear-gradient(90deg, #3b9eff, #00e8c6)
        }

        .fc-pdf {
            top: 174px;
            right: -52px;
            width: 142px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .fc-pdf-ico {
            width: 27px;
            height: 27px;
            border-radius: 7px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px
        }

        .fc-pdf-title {
            font-size: .7rem;
            font-weight: 500
        }

        .fc-pdf-sub {
            font-size: .61rem;
            color: var(--txt3);
            margin-top: 2px
        }

        /* SECTIONS */
        section {
            position: relative;
            z-index: 1
        }

        .si {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 5%
        }

        .sec-lbl {
            font-size: .7rem;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: var(--blue);
            font-weight: 500;
            margin-bottom: 11px;
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: var(--FB)
        }

        .sec-lbl::before {
            content: '';
            display: block;
            width: 16px;
            height: 1px;
            background: var(--blue)
        }

        .sec-title {
            font-family: var(--FD);
            font-size: clamp(1.85rem, 3vw, 2.7rem);
            font-weight: 700;
            line-height: 1.17;
            letter-spacing: -.03em;
            margin-bottom: 15px
        }

        .sec-sub {
            font-size: .96rem;
            font-weight: 300;
            color: var(--txt2);
            line-height: 1.75;
            max-width: 498px
        }

        /* TRUST */
        #trust {
            padding: 52px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, .008);
            overflow: hidden
        }

        .trust-lbl {
            text-align: center;
            font-size: .7rem;
            color: var(--txt3);
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 30px;
            font-family: var(--FB)
        }

        .ltw {
            overflow: hidden
        }

        .lt {
            display: flex;
            gap: 58px;
            width: max-content;
            animation: scroll-l 24s linear infinite
        }

        @keyframes scroll-l {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .li {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: var(--FD);
            font-size: .88rem;
            font-weight: 600;
            color: rgba(238, 241, 248, .26);
            white-space: nowrap;
            cursor: default;
            transition: color .3s;
            flex-shrink: 0;
            letter-spacing: -.01em
        }

        .li:hover {
            color: var(--txt2)
        }

        /* FEATURES */
        #features {
            padding: 108px 0
        }

        .fh {
            margin-bottom: 58px
        }

        .fg {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px
        }

        .fc {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 17px;
            padding: 28px;
            position: relative;
            overflow: hidden;
            transition: all .35s cubic-bezier(.22, 1, .36, 1)
        }

        .fc::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 17px;
            background: linear-gradient(135deg, rgba(59, 158, 255, .11), rgba(139, 92, 246, .08), transparent 55%);
            opacity: 0;
            transition: opacity .35s
        }

        .fc:hover {
            transform: translateY(-5px);
            border-color: var(--borderglow)
        }

        .fc:hover::before {
            opacity: 1
        }

        .fc:hover .fc-glow {
            opacity: 1
        }

        .fc-glow {
            position: absolute;
            top: -48px;
            right: -48px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 158, 255, .11), transparent 70%);
            opacity: 0;
            transition: opacity .35s;
            pointer-events: none
        }

        .fc-iw {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: rgba(59, 158, 255, .07);
            border: 1px solid rgba(59, 158, 255, .1);
            transition: transform .3s
        }

        .fc:hover .fc-iw {
            transform: scale(1.09) rotate(-4deg)
        }

        .fc-title {
            font-family: var(--FD);
            font-size: .98rem;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -.015em
        }

        .fc-desc {
            font-size: .845rem;
            color: var(--txt2);
            line-height: 1.7;
            font-weight: 300
        }

        .fc.feat {
            background: linear-gradient(135deg, rgba(26, 111, 212, .06), rgba(124, 58, 237, .065));
            border-color: rgba(59, 158, 255, .12)
        }

        .fc-tag {
            display: inline-block;
            padding: 2px 9px;
            margin-bottom: 16px;
            background: rgba(59, 158, 255, .08);
            border: 1px solid rgba(59, 158, 255, .17);
            border-radius: 100px;
            font-size: .63rem;
            color: var(--blue);
            letter-spacing: .05em;
            text-transform: uppercase;
            font-weight: 500
        }

        /* CHAT SHOWCASE */
        #cs {
            padding: 108px 0;
            overflow: hidden
        }

        .cs-hd {
            text-align: center;
            margin-bottom: 64px
        }

        .cs-hd .sec-lbl {
            justify-content: center
        }

        .cs-hd .sec-lbl::before {
            display: none
        }

        .cs-hd .sec-title,
        .cs-hd .sec-sub {
            text-align: center
        }

        .cs-hd .sec-sub {
            margin: 0 auto
        }

        .bcw {
            background: rgba(10, 14, 25, .92);
            border: 1px solid var(--border);
            border-radius: 21px;
            overflow: hidden;
            box-shadow: 0 38px 110px rgba(0, 0, 0, .48), 0 0 0 1px rgba(255, 255, 255, .03);
            display: grid;
            grid-template-columns: 235px 1fr;
            height: 495px
        }

        .bcw-sb {
            background: rgba(0, 0, 0, .2);
            border-right: 1px solid var(--border);
            padding: 22px 15px;
            display: flex;
            flex-direction: column;
            gap: 5px
        }

        .bcw-wn {
            font-family: var(--FD);
            font-size: .86rem;
            font-weight: 700;
            margin-bottom: 14px;
            letter-spacing: -.02em
        }

        .bcw-st {
            font-size: .63rem;
            color: var(--txt3);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin: 10px 0 7px
        }

        .bcw-file {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 9px;
            border-radius: 7px;
            font-size: .73rem;
            color: var(--txt2);
            cursor: default;
            transition: all .2s
        }

        .bcw-file.act {
            background: rgba(59, 158, 255, .07);
            border: 1px solid rgba(59, 158, 255, .12);
            color: var(--txt)
        }

        .fext {
            font-size: .59rem;
            padding: 1px 5px;
            border-radius: 3px;
            font-weight: 600
        }

        .fext-x {
            background: rgba(0, 180, 100, .17);
            color: #00cc80
        }

        .fext-c {
            background: rgba(59, 158, 255, .17);
            color: #3b9eff
        }

        .bcw-uz {
            margin-top: auto;
            border: 1px dashed rgba(255, 255, 255, .09);
            border-radius: 9px;
            padding: 13px;
            text-align: center;
            cursor: default
        }

        .bcw-uz p {
            font-size: .69rem;
            color: var(--txt3);
            line-height: 1.5
        }

        .bcw-uz-btn {
            margin-top: 9px;
            padding: 6px 13px;
            border-radius: 6px;
            background: rgba(59, 158, 255, .09);
            border: 1px solid rgba(59, 158, 255, .17);
            font-size: .69rem;
            color: var(--blue);
            display: inline-block
        }

        .bcw-main {
            display: flex;
            flex-direction: column;
            overflow: hidden
        }

        .bcw-hd {
            padding: 13px 19px;
            border-bottom: 1px solid var(--border);
            background: rgba(0, 0, 0, .1);
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .bcw-ht {
            font-family: var(--FD);
            font-size: .83rem;
            font-weight: 600;
            letter-spacing: -.01em
        }

        .bcw-bk {
            font-size: .7rem;
            color: var(--txt3)
        }

        .bcw-msgs {
            flex: 1;
            overflow-y: auto;
            padding: 17px 19px;
            display: flex;
            flex-direction: column;
            gap: 13px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .055) transparent
        }

        .btw {
            background: rgba(10, 16, 32, .7);
            border: 1px solid var(--border);
            border-radius: 11px;
            overflow: hidden;
            overflow-x: auto
        }

        .bt {
            min-width: 480px;
            border-collapse: collapse;
            font-size: .7rem
        }

        .bt th {
            padding: 7px 11px;
            background: rgba(0, 0, 0, .2);
            border-bottom: 1px solid var(--border);
            color: var(--txt3);
            font-size: .65rem;
            font-weight: 500;
            letter-spacing: .04em;
            text-align: left;
            white-space: nowrap
        }

        .bt td {
            padding: 7px 11px;
            border-bottom: 1px solid rgba(255, 255, 255, .03);
            color: var(--txt2);
            vertical-align: top
        }

        .bt tr:last-child td {
            border-bottom: none
        }

        .bt-id {
            font-family: monospace;
            font-size: .63rem;
            color: var(--blue)
        }

        .bcw-exp {
            padding: 7px 13px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end
        }

        .bcw-exp-btn {
            font-size: .67rem;
            color: var(--txt3);
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: default
        }

        .bcw-mu {
            align-self: flex-end;
            background: rgba(255, 255, 255, .065);
            border: 1px solid rgba(255, 255, 255, .085);
            border-radius: 13px 13px 3px 13px;
            padding: 9px 14px;
            max-width: 80%;
            font-size: .76rem;
            color: var(--txt)
        }

        .bcw-ma {
            background: rgba(11, 18, 34, .65);
            border: 1px solid var(--border);
            border-radius: 3px 13px 13px 13px;
            padding: 11px 14px;
            max-width: 88%;
            font-size: .76rem;
            color: var(--txt2);
            line-height: 1.64
        }

        .bcw-link {
            color: var(--blue);
            text-decoration: underline
        }

        .bcw-itbl {
            background: rgba(0, 0, 0, .22);
            border: 1px solid var(--border);
            border-radius: 7px;
            overflow: hidden;
            margin-top: 9px
        }

        .bcw-itbl table {
            width: 100%;
            border-collapse: collapse;
            font-size: .66rem
        }

        .bcw-itbl th {
            padding: 6px 10px;
            background: rgba(0, 0, 0, .17);
            border-bottom: 1px solid var(--border);
            color: var(--txt3);
            font-size: .6rem;
            text-align: left;
            font-weight: 500
        }

        .bcw-itbl td {
            padding: 6px 10px;
            color: var(--txt2)
        }

        .bcw-ir {
            padding: 11px 15px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 8px;
            background: rgba(0, 0, 0, .1)
        }

        .bcw-if {
            flex: 1;
            background: rgba(255, 255, 255, .032);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 9px 13px;
            font-size: .76rem;
            color: var(--txt3);
            font-family: var(--FB)
        }

        .bcw-isend {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            border: none;
            color: #fff;
            font-size: .78rem;
            cursor: default;
            display: flex;
            align-items: center;
            justify-content: center
        }

        /* HOW */
        #how {
            padding: 108px 0
        }

        .how-hd {
            text-align: center;
            margin-bottom: 74px
        }

        .how-hd .sec-lbl {
            justify-content: center
        }

        .how-hd .sec-lbl::before {
            display: none
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            position: relative
        }

        .sline {
            position: absolute;
            top: 37px;
            left: 12%;
            right: 12%;
            height: 1px;
            background: var(--border);
            overflow: hidden
        }

        .sline-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b9eff, #8b5cf6);
            width: 0%;
            transition: width 1.6s cubic-bezier(.4, 0, .2, 1)
        }

        .sitem {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 17px
        }

        .scircle {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            margin-bottom: 24px;
            background: rgba(0, 0, 0, .3);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            flex-shrink: 0;
            transition: border-color .45s, box-shadow .45s
        }

        .scircle.lit {
            border-color: var(--blue);
            box-shadow: 0 0 20px rgba(59, 158, 255, .18)
        }

        .stitle {
            font-family: var(--FD);
            font-size: .98rem;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -.015em
        }

        .sdesc {
            font-size: .82rem;
            color: var(--txt2);
            line-height: 1.65;
            font-weight: 300
        }

        /* TESTIMONIALS */
        #test {
            padding: 108px 0
        }

        .test-hd {
            margin-bottom: 54px
        }

        .test-wrap {
            overflow: hidden
        }

        .test-track {
            display: flex;
            gap: 19px;
            transition: transform .65s cubic-bezier(.22, 1, .36, 1)
        }

        .tcard {
            min-width: 385px;
            flex-shrink: 0;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 17px;
            padding: 28px;
            transition: border-color .3s, box-shadow .3s
        }

        .tcard:hover {
            border-color: var(--borderglow);
            box-shadow: 0 18px 55px rgba(0, 0, 0, .26)
        }

        .tstars {
            color: #f59e0b;
            font-size: .84rem;
            margin-bottom: 14px;
            letter-spacing: 2px
        }

        .tquote {
            font-size: .9rem;
            font-weight: 300;
            font-style: italic;
            color: var(--txt2);
            line-height: 1.75;
            margin-bottom: 20px
        }

        .tauthor {
            display: flex;
            align-items: center;
            gap: 11px
        }

        .tavatar {
            width: 37px;
            height: 37px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            font-weight: 700;
            font-family: var(--FD);
            flex-shrink: 0
        }

        .tname {
            font-size: .84rem;
            font-weight: 500;
            font-family: var(--FD)
        }

        .trole {
            font-size: .72rem;
            color: var(--txt3);
            margin-top: 2px
        }

        .tcontrols {
            display: flex;
            gap: 9px;
            margin-top: 30px;
            align-items: center
        }

        .tbtn {
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--txt2);
            font-size: .92rem;
            cursor: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s
        }

        .tbtn:hover {
            border-color: var(--blue);
            color: var(--blue)
        }

        .tdots {
            display: flex;
            gap: 6px;
            margin-left: 6px
        }

        .tdot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--border);
            transition: all .3s;
            cursor: none
        }

        .tdot.on {
            background: var(--blue);
            width: 19px;
            border-radius: 3px
        }

        /* PRICING */
        #pricing {
            padding: 108px 0
        }

        .ph {
            text-align: center;
            margin-bottom: 16px
        }

        .ph .sec-lbl {
            justify-content: center
        }

        .ph .sec-lbl::before {
            display: none
        }

        .ptoggle {
            display: flex;
            align-items: center;
            gap: 11px;
            justify-content: center;
            margin-bottom: 52px
        }

        .ptlbl {
            font-size: .84rem;
            color: var(--txt3);
            transition: color .2s
        }

        .ptlbl.on {
            color: var(--txt)
        }

        .ptsw {
            width: 44px;
            height: 23px;
            border-radius: 11.5px;
            background: rgba(59, 158, 255, .11);
            border: 1px solid rgba(59, 158, 255, .17);
            position: relative;
            cursor: none;
            transition: background .3s
        }

        .ptthumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: var(--blue);
            transition: transform .3s
        }

        .ptsw.yr .ptthumb {
            transform: translateX(21px)
        }

        .ptsw.yr {
            background: rgba(59, 158, 255, .22)
        }

        .sbadge {
            font-size: .67rem;
            padding: 2px 8px;
            background: rgba(0, 232, 198, .09);
            color: var(--cyan);
            border: 1px solid rgba(0, 232, 198, .17);
            border-radius: 100px
        }

        .pgrid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px
        }

        .pcard {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 19px;
            padding: 32px;
            position: relative;
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
            overflow: hidden
        }

        .pcard:hover {
            transform: translateY(-7px)
        }

        .pcard.pop {
            background: linear-gradient(145deg, rgba(26, 111, 212, .08), rgba(124, 58, 237, .065));
            border-color: rgba(59, 158, 255, .17);
            transform: scale(1.025)
        }

        .pcard.pop:hover {
            transform: scale(1.025) translateY(-7px)
        }

        .pop-badge {
            position: absolute;
            top: 19px;
            right: 19px;
            padding: 3px 11px;
            background: linear-gradient(135deg, #3b9eff, #8b5cf6);
            border-radius: 100px;
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .04em
        }

        .pname {
            font-family: var(--FD);
            font-size: 1.02rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -.02em
        }

        .pdesc {
            font-size: .79rem;
            color: var(--txt2);
            margin-bottom: 24px;
            font-weight: 300
        }

        .pamt {
            display: flex;
            align-items: baseline;
            gap: 3px;
            margin-bottom: 24px
        }

        .pcur {
            font-size: .98rem;
            color: var(--txt2);
            margin-top: 5px
        }

        .pnum {
            font-family: var(--FD);
            font-size: 2.7rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -.04em
        }

        .pper {
            font-size: .77rem;
            color: var(--txt3)
        }

        .pfeats {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px
        }

        .pfeats li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .845rem;
            color: var(--txt2);
            font-weight: 300
        }

        .chk {
            color: var(--cyan);
            font-size: .83rem;
            flex-shrink: 0
        }

        .pcta {
            width: 100%;
            padding: 12px;
            border-radius: 9px;
            font-family: var(--FB);
            font-size: .87rem;
            font-weight: 500;
            cursor: none;
            transition: all .25s;
            border: 1px solid var(--borderglow);
            background: transparent;
            color: var(--txt);
            letter-spacing: .01em
        }

        .pcta:hover {
            background: rgba(59, 158, 255, .07)
        }

        .pcta.hl {
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            border: none
        }

        .pcta.hl:hover {
            opacity: .88
        }

        /* FINAL CTA */
        #fcta {
            padding: 128px 5%;
            text-align: center;
            position: relative;
            overflow: hidden
        }

        .fcta-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 68% 68% at 50% 50%, rgba(26, 90, 200, .17) 0%, transparent 70%)
        }

        .fcta-line {
            position: absolute;
            top: 0;
            left: 8%;
            right: 8%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #3b9eff, #8b5cf6, transparent)
        }

        .fcta-in {
            position: relative;
            z-index: 1
        }

        .fcta-eye {
            font-size: .71rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 16px;
            font-family: var(--FB);
            font-weight: 500
        }

        .fcta-title {
            font-family: var(--FD);
            font-size: clamp(2.1rem, 3.7vw, 3.3rem);
            font-weight: 800;
            letter-spacing: -.035em;
            margin-bottom: 17px;
            line-height: 1.09
        }

        .fcta-sub {
            font-size: .98rem;
            color: var(--txt2);
            margin-bottom: 38px;
            font-weight: 300
        }

        .fcta-btns {
            display: flex;
            gap: 13px;
            justify-content: center;
            flex-wrap: wrap
        }

        .btn-pulse {
            padding: 14px 36px;
            border-radius: 11px;
            background: linear-gradient(135deg, #1a6fd4, #7c3aed);
            border: none;
            color: #fff;
            font-family: var(--FB);
            font-size: .95rem;
            font-weight: 500;
            cursor: none;
            letter-spacing: .01em;
            animation: cpulse 2.8s ease-in-out infinite;
            transition: transform .2s
        }

        .btn-pulse:hover {
            transform: scale(1.02)
        }

        @keyframes cpulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(59, 158, 255, 0), 0 8px 26px rgba(26, 111, 212, .28)
            }

            50% {
                box-shadow: 0 0 0 9px rgba(59, 158, 255, 0), 0 10px 38px rgba(59, 158, 255, .38)
            }
        }

        /* FOOTER */
        footer {
            /* border-top: 1px solid var(--border); */
            padding: 56px 5% 34px
        }

        .fi {
            max-width: 1280px;
            margin: 0 auto
        }

        .ftop {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 52px;
            margin-bottom: 52px
        }

        .fbrand p {
            font-size: .82rem;
            color: var(--txt3);
            margin-top: 11px;
            line-height: 1.7;
            max-width: 245px;
            font-weight: 300
        }

        .fc-title {
            font-family: var(--FD);
            font-size: .8rem;
            font-weight: 600;
            margin-bottom: 14px;
            letter-spacing: -.01em
        }

        .flinks {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .flinks a {
            font-size: .81rem;
            color: var(--txt3);
            text-decoration: none;
            transition: color .2s;
            font-weight: 300
        }

        .flinks a:hover {
            color: var(--txt)
        }

        .fbot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 26px;
            border-top: 1px solid var(--border)
        }

        .fcopy {
            font-size: .77rem;
            color: var(--txt3);
            font-weight: 300
        }

        .fsocs {
            display: flex;
            gap: 9px
        }

        .fsoc {
            width: 31px;
            height: 31px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            cursor: none;
            color: var(--txt3);
            transition: all .2s;
            text-decoration: none;
            font-family: var(--FB)
        }

        .fsoc:hover {
            border-color: var(--blue);
            color: var(--blue)
        }

        /* REVEAL */
        .rev {
            opacity: 0;
            transform: translateY(26px)
        }

        .rev.vis {
            opacity: 1;
            transform: translateY(0);
            transition: opacity .7s ease, transform .7s cubic-bezier(.22, 1, .36, 1)
        }

        .d1 {
            transition-delay: .1s
        }

        .d2 {
            transition-delay: .22s
        }

        .d3 {
            transition-delay: .34s
        }

        .d4 {
            transition-delay: .46s
        }

        .d5 {
            transition-delay: .58s
        }

        /* RESPONSIVE */
        @media(max-width:1024px) {
            .hero-inner {
                grid-template-columns: 1fr
            }

            .hero-visual {
                height: 430px
            }

            .fc-ai,
            .fc-pdf {
                display: none
            }

            .fc-acc {
                right: -18px
            }

            .fg {
                grid-template-columns: repeat(2, 1fr)
            }

            .bcw {
                grid-template-columns: 1fr
            }

            .bcw-sb {
                display: none
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
                gap: 38px
            }

            .sline {
                display: none
            }

            .pgrid {
                grid-template-columns: 1fr;
                max-width: 390px;
                margin: 0 auto
            }

            .pcard.pop {
                transform: none
            }

            .ftop {
                grid-template-columns: 1fr 1fr;
                gap: 34px
            }
        }

        @media(max-width:640px) {
            nav .nav-links {
                display: none
            }

            .fg {
                grid-template-columns: 1fr
            }

            .steps {
                grid-template-columns: 1fr
            }

            .tcard {
                min-width: 285px
            }

            .hero-stats {
                gap: 20px
            }

            .ftop {
                grid-template-columns: 1fr
            }

            .chat-win {
                grid-template-columns: 1fr
            }

            .cw-sb {
                display: none
            }
        }
    </style>
</head>

<body>

    <div id="cur"></div>
    <div id="cur-ring"></div>
    <div id="cur-glow"></div>
    <div class="bg-grid"></div>

    <!-- LOADER -->
    <div id="loader">
        <div class="ld-inner">
            <div class="ld-icon">📊</div>
            <div class="ld-name">SheetsGPT</div>
            <div class="ld-bar">
                <div class="ld-fill"></div>
            </div>
        </div>
    </div>

    <!-- NAV -->
    <nav id="nav">
        <a href="#" class="nav-logo">
            <div class="logo-icon">📊</div>SheetsGPT
        </a>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#cs">Showcase</a></li>
            <li><a href="#how">How It Works</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
        </ul>
        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a class="btn-nav" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="btn-ghost" href="{{ route('login') }}">Sign In</a>
                    <a class="btn-ghost" href="{{ route('register') }}">Register</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- HERO -->
    <section id="hero">
        <div class="orb1"></div>
        <div class="orb2"></div>
        <div class="hero-inner">
            <!-- Copy -->
            <div>
                <div class="hero-badge" id="hbadge">
                    <div class="bdot"></div>AI-Powered Spreadsheet Intelligence
                </div>
                <h1 class="htitle" id="htitle">Query Your<br /><span class="gtxt">Spreadsheets</span><br />Like a Pro
                </h1>
                <p class="hero-sub" id="hsub">Upload XLSX and CSV files, ask anything in plain English, and get instant
                    AI-powered answers and clean PDF exports.</p>
                <div class="hero-ctas" id="hctas">
                    <button class="btn-xl btn-xl-p"><span>Get Started Free →</span></button>
                    <!-- <button class="btn-xl btn-xl-g">
                        <div class="play-i">▶</div>Live Demo
                    </button> -->
                </div>
                <div class="hero-stats" id="hstats">
                    <div>
                        <div class="snum"><span class="cnt" data-to="50000" data-fmt="c">0</span>+</div>
                        <div class="slbl">Files Analyzed</div>
                    </div>
                    <div>
                        <div class="snum"><span class="cnt" data-to="12000" data-fmt="c">0</span>+</div>
                        <div class="slbl">Happy Users</div>
                    </div>
                    <div>
                        <div class="snum"><span class="cnt" data-to="98">0</span>%</div>
                        <div class="slbl">Accuracy Rate</div>
                    </div>
                </div>
            </div>

            <!-- Chat Mockup -->
            <div class="hero-visual" id="hvisual">
                <div class="chat-win">
                    <!-- sidebar -->
                    <div class="cw-sb">
                        <div class="cw-wslbl">My First workspace</div>
                        <div class="cw-sec">Documents Context</div>
                        <div class="cw-chip">
                            <div class="cw-chip-dot"></div>customers-1000.csv
                        </div>
                        <div class="cw-div"></div>
                        <div class="cw-upload">
                            <div class="cw-choose">Choose files</div>
                            <div class="cw-upl-btn">Upload &amp; Parse</div>
                        </div>
                    </div>
                    <!-- chat -->
                    <div class="cw-chat">
                        <div class="cw-topbar">
                            <div class="cw-topbar-title">My First workspace</div>
                            <div class="cw-back">← Back to Workspaces</div>
                        </div>
                        <div class="cw-msgs">
                            <!-- Table result -->
                            <div class="msg-tbl-wrap">
                                <table class="msg-tbl">
                                    <thead>
                                        <tr>
                                            <th>Index</th>
                                            <th>Customer Id</th>
                                            <th>First</th>
                                            <th>Last</th>
                                            <th>Company</th>
                                            <th>City</th>
                                            <th>Country</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>36</td>
                                            <td class="td-mono">B98BacEebC40DBb</td>
                                            <td>Jill</td>
                                            <td>Mayo</td>
                                            <td>Woodard, Haas and Mason</td>
                                            <td>Port Carlside</td>
                                            <td>Cuba</td>
                                        </tr>
                                        <tr>
                                            <td>49</td>
                                            <td class="td-mono">e6D85CcfDE7ABEd</td>
                                            <td>Valerie</td>
                                            <td>Haney</td>
                                            <td>Delgado, Rubio and Rose</td>
                                            <td>Harryview</td>
                                            <td>Cuba</td>
                                        </tr>
                                        <tr>
                                            <td>741</td>
                                            <td class="td-mono">D39B2f3649fA9dF</td>
                                            <td>Troy</td>
                                            <td>Fritz</td>
                                            <td>Mercado PLC</td>
                                            <td>South Taylor</td>
                                            <td>Cuba</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="exp-row">
                                    <div class="exp-btn">↓ Export PDF</div>
                                </div>
                            </div>
                            <!-- User -->
                            <div class="msg-u">give me the website of the customers who are from Goodman company</div>
                            <!-- AI -->
                            <div class="msg-a">
                                There are no customers with the exact company name 'Goodman company' in the provided
                                data.
                                <div class="exp-row" style="padding:6px 0 0;justify-content:flex-end;display:flex;">
                                    <div class="exp-btn">↓ Export PDF</div>
                                </div>
                            </div>
                            <!-- User -->
                            <div class="msg-u">try Goodman, Carrillo and Stein</div>
                            <!-- AI + table -->
                            <div class="msg-a">
                                Here is the website for the customer from the company 'Goodman, Carrillo and Stein':
                                <div class="msg-a-inner-tbl">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Website</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="msg-a-link">https://www.willis-todd.net/</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="display:flex;justify-content:flex-end;margin-top:7px;">
                                    <div class="exp-btn">↓ Export PDF</div>
                                </div>
                            </div>
                        </div>
                        <div class="cw-input-bar">
                            <div class="cw-inp">Ask about your documents...</div>
                            <div class="cw-send">▶</div>
                        </div>
                    </div>
                </div>
                <!-- Chips -->
                <div class="fchip fc-ai">
                    <div class="fc-lbl">🤖 AI Insight</div>
                    <div class="fc-txt">Found <strong>3 matches</strong> across your CSV. Try refining your query.</div>
                </div>
                <div class="fchip fc-acc">
                    <div class="fc-num">98.4%</div>
                    <div class="fc-sub">Query Accuracy</div>
                    <div class="fc-bar">
                        <div class="fc-bar-fill"></div>
                    </div>
                </div>
                <div class="fchip fc-pdf">
                    <div class="fc-pdf-ico">📄</div>
                    <div>
                        <div class="fc-pdf-title">PDF Exported</div>
                        <div class="fc-pdf-sub">Just now · 1.8 MB</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST -->
    <section id="trust">
        <div class="trust-lbl">Trusted by teams at leading companies</div>
        <div class="ltw">
            <div class="lt">
                <div class="li"><span>◆</span> Accenture</div>
                <div class="li"><span>●</span> Shopify</div>
                <div class="li"><span>▲</span> Notion</div>
                <div class="li"><span>■</span> Figma</div>
                <div class="li"><span>✦</span> Airtable</div>
                <div class="li"><span>◈</span> Linear</div>
                <div class="li"><span>◉</span> Vercel</div>
                <div class="li"><span>⬡</span> Databricks</div>
                <div class="li"><span>◆</span> Accenture</div>
                <div class="li"><span>●</span> Shopify</div>
                <div class="li"><span>▲</span> Notion</div>
                <div class="li"><span>■</span> Figma</div>
                <div class="li"><span>✦</span> Airtable</div>
                <div class="li"><span>◈</span> Linear</div>
                <div class="li"><span>◉</span> Vercel</div>
                <div class="li"><span>⬡</span> Databricks</div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features">
        <div class="si">
            <div class="fh">
                <div class="sec-lbl rev">Features</div>
                <div class="sec-title rev d1">Everything you need to<br /><span class="gtxt">master your data</span>
                </div>
                <p class="sec-sub rev d2">From instant uploads to AI-powered queries and clean PDF exports — SheetsGPT
                    handles it all so you focus on decisions, not wrangling.</p>
            </div>
            <div class="fg">
                <div class="fc rev d1">
                    <div class="fc-glow"></div>
                    <div class="fc-iw">🧠</div>
                    <div class="fc-title">Natural Language Queries</div>
                    <p class="fc-desc">Ask questions in plain English. "What's the average revenue by region?" — no SQL,
                        no formulas, no friction.</p>
                </div>
                <div class="fc feat rev d2">
                    <div class="fc-glow"></div>
                    <div class="fc-tag">✨ Core Feature</div>
                    <div class="fc-iw" style="background:rgba(139,92,246,.07);border-color:rgba(139,92,246,.12)">📂
                    </div>
                    <div class="fc-title">Multi-File Workspaces</div>
                    <p class="fc-desc">Upload multiple XLSX and CSV files into one workspace. The AI queries across all
                        of them simultaneously.</p>
                </div>
                <div class="fc rev d3">
                    <div class="fc-glow"></div>
                    <div class="fc-iw">🔍</div>
                    <div class="fc-title">Deep Data Analysis</div>
                    <p class="fc-desc">Detect trends, outliers, and correlations automatically. AI surfaces what matters
                        most without you having to dig.</p>
                </div>
                <div class="fc rev d2">
                    <div class="fc-glow"></div>
                    <div class="fc-iw" style="background:rgba(0,232,198,.06);border-color:rgba(0,232,198,.11)">📄</div>
                    <div class="fc-title">PDF &amp; Markdown Export</div>
                    <p class="fc-desc">Export your analysis as beautifully formatted PDFs or copy results as Markdown —
                        your insights, anywhere.</p>
                </div>
                <div class="fc rev d3">
                    <div class="fc-glow"></div>
                    <div class="fc-iw">💬</div>
                    <div class="fc-title">Conversational Context</div>
                    <p class="fc-desc">The AI remembers previous questions within the session. Refine, follow up, and
                        drill down naturally.</p>
                </div>
                <div class="fc rev d4">
                    <div class="fc-glow"></div>
                    <div class="fc-iw" style="background:rgba(139,92,246,.06);border-color:rgba(139,92,246,.11)">🔐
                    </div>
                    <div class="fc-title">Secure Workspaces</div>
                    <p class="fc-desc">Data is encrypted at rest and in transit. Your spreadsheets live in your private
                        workspace — no third-party sharing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CHAT SHOWCASE -->
    <section id="cs">
        <div class="si">
            <div class="cs-hd">
                <div class="sec-lbl rev">Live Workspace</div>
                <div class="sec-title rev d1">Your data, <span class="gtxt">fully queryable</span></div>
                <p class="sec-sub rev d2">Upload files, ask questions, get structured answers. No dashboards, no config
                    — just a conversation with your data.</p>
            </div>

            <div class="bcw rev d2">
                <!-- sidebar -->
                <div class="bcw-sb">
                    <div class="bcw-wn">My First workspace</div>
                    <div class="bcw-st">Documents Context</div>
                    <div class="bcw-file act"><span class="fext fext-c">CSV</span>customers-1000.csv</div>
                    <div class="bcw-file"><span class="fext fext-x">XLSX</span>Q4_Sales.xlsx</div>
                    <div class="bcw-file"><span class="fext fext-c">CSV</span>inventory.csv</div>
                    <div class="bcw-uz">
                        <p>Drop files here or click to upload</p>
                        <div class="bcw-uz-btn">Upload &amp; Parse</div>
                    </div>
                </div>
                <!-- main -->
                <div class="bcw-main">
                    <div class="bcw-hd">
                        <div class="bcw-ht">My First workspace</div>
                        <div class="bcw-bk">← Back to Workspaces</div>
                    </div>
                    <div class="bcw-msgs">
                        <!-- table -->
                        <div class="btw">
                            <div style="overflow-x:auto">
                                <table class="bt">
                                    <thead>
                                        <tr>
                                            <th>Index</th>
                                            <th>Customer Id</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company</th>
                                            <th>City</th>
                                            <th>Country</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>36</td>
                                            <td class="bt-id">B98BacEebC40DBb</td>
                                            <td>Jill</td>
                                            <td>Mayo</td>
                                            <td>Woodard, Haas and Mason</td>
                                            <td>Port Carlside</td>
                                            <td>Cuba</td>
                                        </tr>
                                        <tr>
                                            <td>49</td>
                                            <td class="bt-id">e6D85CcfDE7ABEd</td>
                                            <td>Valerie</td>
                                            <td>Haney</td>
                                            <td>Delgado, Rubio and Rose</td>
                                            <td>Harryview</td>
                                            <td>Cuba</td>
                                        </tr>
                                        <tr>
                                            <td>741</td>
                                            <td class="bt-id">D39B2f3649fA9dF</td>
                                            <td>Troy</td>
                                            <td>Fritz</td>
                                            <td>Mercado PLC</td>
                                            <td>South Taylor</td>
                                            <td>Cuba</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="bcw-exp">
                                <div class="bcw-exp-btn">↓ Export PDF</div>
                            </div>
                        </div>

                        <!-- user -->
                        <div class="bcw-mu">give me the website of the customers who are from Goodman company</div>

                        <!-- ai -->
                        <div class="bcw-ma">
                            There are no customers with the exact company name 'Goodman company' in the provided data.
                            <div class="bcw-exp">
                                <div class="bcw-exp-btn">↓ Export PDF</div>
                            </div>
                        </div>

                        <!-- user -->
                        <div class="bcw-mu">try Goodman, Carrillo and Stein</div>

                        <!-- ai + table -->
                        <div class="bcw-ma">
                            Here is the website for the customer from the company 'Goodman, Carrillo and Stein':
                            <div class="bcw-itbl">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Website</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bcw-link">https://www.willis-todd.net/</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="bcw-exp">
                                <div class="bcw-exp-btn">↓ Export PDF</div>
                            </div>
                        </div>
                    </div>
                    <div class="bcw-ir">
                        <div class="bcw-if">Ask about your documents...</div>
                        <div class="bcw-isend">▶</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how">
        <div class="si">
            <div class="how-hd">
                <div class="sec-lbl rev">How It Works</div>
                <div class="sec-title rev d1">From upload to <span class="gtxt">insight</span> in 4 steps</div>
            </div>
            <div class="steps" id="steps">
                <div class="sline">
                    <div class="sline-fill" id="slfill"></div>
                </div>
                <div class="sitem rev d1">
                    <div class="scircle" id="sc1">📁</div>
                    <div class="stitle">Create Workspace</div>
                    <p class="sdesc">Sign in and create a named workspace — your dedicated environment for a project or
                        dataset.</p>
                </div>
                <div class="sitem rev d2">
                    <div class="scircle" id="sc2">⬆️</div>
                    <div class="stitle">Upload Files</div>
                    <p class="sdesc">Drag &amp; drop XLSX or CSV files. Upload multiple and they're instantly indexed by
                        AI.</p>
                </div>
                <div class="sitem rev d3">
                    <div class="scircle" id="sc3">💬</div>
                    <div class="stitle">Query in English</div>
                    <p class="sdesc">Ask anything naturally. Get instant answers, structured tables, and AI-generated
                        insights.</p>
                </div>
                <div class="sitem rev d4">
                    <div class="scircle" id="sc4">📤</div>
                    <div class="stitle">Export Results</div>
                    <p class="sdesc">Download as PDF or copy the Markdown. Share insights anywhere in seconds.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials">
        <div class="si">
            <div class="test-hd">
                <div class="sec-lbl rev">Testimonials</div>
                <div class="sec-title rev d1">Loved by data-driven <span class="gtxt">teams</span></div>
            </div>
            <div class="test-wrap rev d2">
                <div class="test-track" id="ttrack">
                    <div class="tcard">
                        <div class="tstars">★★★★★</div>
                        <p class="tquote">"SheetsGPT replaced hours of pivot table work. I just ask what I want to know
                            and it delivers. The PDF exports are clean too."</p>
                        <div class="tauthor">
                            <div class="tavatar" style="background:linear-gradient(135deg,#1a6fd4,#3b9eff)">SL</div>
                            <div>
                                <div class="tname">Sarah Lin</div>
                                <div class="trole">Head of Finance · Accenture</div>
                            </div>
                        </div>
                    </div>
                    <div class="tcard">
                        <div class="tstars">★★★★★</div>
                        <p class="tquote">"I threw 12 CSV files at it from different departments. It stitched them
                            together and answered cross-file questions instantly."</p>
                        <div class="tauthor">
                            <div class="tavatar" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">MR</div>
                            <div>
                                <div class="tname">Marcus Reid</div>
                                <div class="trole">Data Lead · Shopify</div>
                            </div>
                        </div>
                    </div>
                    <div class="tcard">
                        <div class="tstars">★★★★★</div>
                        <p class="tquote">"Our sales team generates weekly insight reports in minutes instead of days.
                            Genuinely transformative for non-technical folks."</p>
                        <div class="tauthor">
                            <div class="tavatar" style="background:linear-gradient(135deg,#059669,#00e8c6)">AK</div>
                            <div>
                                <div class="tname">Aisha Khan</div>
                                <div class="trole">VP Sales · Notion</div>
                            </div>
                        </div>
                    </div>
                    <div class="tcard">
                        <div class="tstars">★★★★★</div>
                        <p class="tquote">"The workspace concept is brilliant. I keep quarterly files together and just
                            ask questions. It remembers context across the session."</p>
                        <div class="tauthor">
                            <div class="tavatar" style="background:linear-gradient(135deg,#dc2626,#f87171)">JP</div>
                            <div>
                                <div class="tname">James Park</div>
                                <div class="trole">Product Manager · Figma</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tcontrols">
                <button class="tbtn" id="tprev">←</button>
                <button class="tbtn" id="tnext">→</button>
                <div class="tdots" id="tdots">
                    <div class="tdot on"></div>
                    <div class="tdot"></div>
                    <div class="tdot"></div>
                    <div class="tdot"></div>
                </div>
            </div>
        </div>
    </section>




    <!-- FOOTER -->
    <footer>
        <div class="fi">
            <div class="fbot">
                <div class="fcopy">© 2025 SheetsGPT. All rights reserved.</div>
                <div class="fsocs"><a href="#" class="fsoc">𝕏</a><a href="#" class="fsoc">in</a><a href="#"
                        class="fsoc">gh</a><a href="#" class="fsoc">yt</a></div>
            </div>
        </div>
    </footer>

    <script>
        // LOADER
        window.addEventListener('load', () => setTimeout(() => {
            document.getElementById('loader').classList.add('out');
            initHero();
        }, 1050));

        // CURSOR
        const cur = document.getElementById('cur'), ring = document.getElementById('cur-ring'), glow = document.getElementById('cur-glow');
        document.addEventListener('mousemove', e => {
            cur.style.left = e.clientX + 'px'; cur.style.top = e.clientY + 'px';
            ring.style.left = e.clientX + 'px'; ring.style.top = e.clientY + 'px';
            glow.style.left = e.clientX + 'px'; glow.style.top = e.clientY + 'px';
        });

        // HERO ENTRANCE
        function initHero() {
            const items = [
                { id: 'hbadge', d: 70, y: 14 }, { id: 'htitle', d: 210, y: 26 }, { id: 'hsub', d: 370, y: 20 },
                { id: 'hctas', d: 510, y: 16 }, { id: 'hstats', d: 660, y: 12 }, { id: 'hvisual', d: 280, y: 0, x: 34 }
            ];
            items.forEach(({ id, d, y, x = 0 }) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.style.opacity = '0'; el.style.transform = `translateY(${y}px) translateX(${x}px)`;
                setTimeout(() => {
                    el.style.transition = 'opacity .75s ease,transform .78s cubic-bezier(.22,1,.36,1)';
                    el.style.opacity = '1'; el.style.transform = 'translateY(0) translateX(0)';
                }, d);
            });
            setTimeout(() => runCounters('#hstats .cnt', 1600), 860);
        }

        // COUNTERS
        function runCounters(sel, dur) {
            document.querySelectorAll(sel).forEach(el => {
                const to = +el.dataset.to, fmt = el.dataset.fmt;
                const t0 = performance.now();
                const tick = now => {
                    const p = Math.min((now - t0) / dur, 1), v = Math.round(to * (1 - Math.pow(1 - p, 3)));
                    el.textContent = fmt === 'c' ? v.toLocaleString() : v;
                    if (p < 1) requestAnimationFrame(tick); else el.textContent = fmt === 'c' ? to.toLocaleString() : to;
                };
                requestAnimationFrame(tick);
            });
        }

        // SCROLL REVEAL
        const ro = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) e.target.classList.add('vis') }), { threshold: .13 });
        document.querySelectorAll('.rev').forEach(el => ro.observe(el));

        // STEPS
        const stObs = new IntersectionObserver(es => {
            if (es[0].isIntersecting) {
                document.getElementById('slfill').style.width = '100%';
                ['sc1', 'sc2', 'sc3', 'sc4'].forEach((id, i) => setTimeout(() => document.getElementById(id)?.classList.add('lit'), i * 360));
            }
        }, { threshold: .35 });
        const stEl = document.getElementById('steps'); if (stEl) stObs.observe(stEl);

        // HERO PARALLAX
        const hv = document.getElementById('hvisual');
        document.getElementById('hero')?.addEventListener('mousemove', e => {
            if (!hv) return;
            const r = hv.getBoundingClientRect();
            const dx = (e.clientX - r.left - r.width / 2) / r.width;
            const dy = (e.clientY - r.top - r.height / 2) / r.height;
            hv.style.transform = `perspective(900px) rotateY(${dx * 6}deg) rotateX(${-dy * 4}deg)`;
            hv.style.transition = 'transform .08s linear';
        });
        document.getElementById('hero')?.addEventListener('mouseleave', () => {
            if (hv) { hv.style.transform = 'perspective(900px) rotateY(0) rotateX(0)'; hv.style.transition = 'transform .55s ease'; }
        });

        // TESTIMONIALS
        let sl = 0; const tot = 4, tt = document.getElementById('ttrack'), dts = document.querySelectorAll('.tdot');
        function goSl(n) {
            sl = ((n % tot) + tot) % tot;
            const w = document.querySelector('.tcard')?.offsetWidth || 385;
            tt.style.transform = `translateX(-${sl * (w + 19)}px)`;
            dts.forEach((d, i) => d.classList.toggle('on', i === sl));
        }
        document.getElementById('tnext')?.addEventListener('click', () => goSl(sl + 1));
        document.getElementById('tprev')?.addEventListener('click', () => goSl(sl - 1));
        dts.forEach((d, i) => d.addEventListener('click', () => goSl(i)));
        setInterval(() => goSl(sl + 1), 4800);

        // PRICING
        let yr = false;
        document.getElementById('ptsw')?.addEventListener('click', () => {
            yr = !yr;
            document.getElementById('ptsw').classList.toggle('yr', yr);
            document.getElementById('plbl-m').classList.toggle('on', !yr);
            document.getElementById('plbl-y').classList.toggle('on', yr);
            document.querySelectorAll('.pnum').forEach(el => {
                const to = yr ? +el.dataset.y : +el.dataset.m;
                if (isNaN(to)) return;
                const from = +el.textContent || 0, t0 = performance.now(), dur = 380;
                const tick = now => {
                    const p = Math.min((now - t0) / dur, 1);
                    el.textContent = Math.round(from + (to - from) * p);
                    if (p < 1) requestAnimationFrame(tick); else el.textContent = to;
                };
                requestAnimationFrame(tick);
            });
        });

        // NAV
        window.addEventListener('scroll', () => {
            document.getElementById('nav').style.background =
                window.scrollY > 38 ? 'rgba(8,9,14,.95)' : 'rgba(8,9,14,.75)';
        });

        // RIPPLE
        document.querySelectorAll('.btn-xl-p,.btn-pulse').forEach(btn => {
            btn.addEventListener('click', e => {
                const rp = document.createElement('span'), r = btn.getBoundingClientRect();
                Object.assign(rp.style, { position: 'absolute', width: '4px', height: '4px', borderRadius: '50%', background: 'rgba(255,255,255,.3)', pointerEvents: 'none', left: e.clientX - r.left + 'px', top: e.clientY - r.top + 'px', transform: 'scale(0)', animation: 'rp .5s ease-out forwards' });
                btn.appendChild(rp); setTimeout(() => rp.remove(), 500);
            });
        });
        const ss = document.createElement('style'); ss.textContent = '@keyframes rp{to{transform:scale(85);opacity:0}}'; document.head.appendChild(ss);
    </script>
</body>

</html>