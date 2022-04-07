(function () {
    const tweetField = document.getElementById('tweetField')
    const btnTweet = document.getElementById('btnTweet')

    tweetField.onkeyup = e => {
        if (tweetField.value.length > 0 && e.code !== 'Space') btnTweet.removeAttribute('disabled')
        if (tweetField.value.length === 0) btnTweet.setAttribute('disabled', 'disabled')
    }
})() 


