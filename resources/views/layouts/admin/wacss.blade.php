<style>
.whatsapp-float {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 1000;
}

.whatsapp-link {
    display: block;
    transition: all 0.3s ease;
    text-decoration: none;
    animation: pulse 2s infinite;
}

.whatsapp-link:hover {
    transform: scale(1.1);
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

@media (max-width: 768px) {
    .whatsapp-float {
        bottom: 20px;
        right: 20px;
    }

    .whatsapp-link img {
        width: 55px;
        height: 55px;
    }
}
</style>
