<p>November 2025</p>

<p>My background is as a Tax Adviser. My main work was preparing and submitting tax returns for hundreds of clients each
    year, and a big part of this involved phoning HMRC, for example to chase refunds, query their
    calculations, or to deal with issues that their system wasn't picking up automatically. </p>

<p>HMRC provide an agent helpline to facilitate this. It was always answered promptly and the workers who answered were
    generally knowledgeable and helpful. Unfortunately, when covid
    came HMRC linked the agent helpline into the main phone line, and at the same time service on their main line
    deteriorated massively. Five years on from covid, it's never changed back.</p>

<p>Although official figures generally claimed that calls were being answered in around 20 minutes, in years of
    contacting
    HMRC very frequently
    since covid I never had a call answered in less than 40 minutes. My routine was to dial the agent line, spend a
    couple of minutes getting through the 'press a number' options, then once I reached the 'your call is important to
    us' message I put the phone aside, took the dog for a walk, had lunch, then at around the 40 minute mark I
    went back to wait for HMRC to answer. I never missed them picking up - they must have a separate 40 minute queue
    that calls have to sit in before then being diverted to the main queue. Maybe that's when they start counting, and
    from here your call is likely to be
    answered within around 20 minutes. At this point there's a decent chance the line will cut out and you'll have to
    start again at the beginning of the queue. Assuming the line doesn't cut out, there's maybe a 50/50 chance that the
    person you speak with will understand your query, or be able to help.</p>

<p>So when I switched to developing software for making tax digital, I came to HMRC's developer hub with a somewhat
    sceptical mindset. You can contact HMRC's developer support by message through the developer hub, and
    they promise a response
    within 2 working days. I tried it out, and to my surprise messages were generally answered within a week at most,
    which seemed very decent to me in comparison to the service I was used to on the tax side. I'm not sure whether it's
    because my initial questions were very basic, or because HMRC are getting busier as the start of Making Tax
    Digital approaches, but the response time has since deteriorated towards something that more closely matches
    my previous experiences, with responses usually taking around a month rather than 2 days, and many queries either
    not being answered at all, or the response being that the query has been passed to another department within HMRC
    which means, with 100% certainty, that nothing further will ever be heard.</p>

<p>Under Making Tax Digital HMRC deliberately don't provide the facility to file through their
    website, instead they make available a series of endpoints to and from which data can be sent and retrieved, and
    they rely
    on external software providers to create sites that taxpayers can use to communicate with these endpoints. For
    example, when
    Making Tax Digital For VAT was introduced, HMRC closed down the facility to submit a VAT return on
    their website, and replaced it with a list of commercial providers. I don't really understand the logic behind this,
    but it is what it is.
</p>

<p>By law, all connections to HMRC's Making Tax Digital endpoints must collect data about the user, and pass this data
    to HMRC with each request. These are the 'Fraud Prevention Headers'. So when you look up your latest tax
    calculation, or submit your latest update, HMRC are
    not only checking that you are authorised through your gov.uk account, they also collect various information about
    the user making the submission, such as the user's IP address and information about the device they are using.</p>

<p>This seems to be a reasonable idea, presumably the collected information
    might be helpful
    in a serious fraud case after devices have been seized, or maybe HMRC are analysing each request and will
    somehow be more sceptical if a request is made from a different IP address and on a different device than the one
    you normally use.</p>


<p>Before an application is given access to live data, it has to be submitted to HMRC for approval. I
    have made two applications, one for VAT (OpenVat) and one for Income Tax (TaxUpdates). From my side, as the
    developer, the approval process is very straightforward, it basically just involves submitting a form to HMRC. HMRC
    appear to specifically check
    three things - that the application includes a privacy policy, that it includes terms of service, and that Fraud
    Prevention Headers are being sent. They don't seem to actually test the application itself, or even look at it, as
    far as I'm aware (beyond looking at the terms and privacy pages). In a way, this seems to be ok - the point of MTD
    is that numerous commercial applications will be made available to taxpayers, and presumably the market will decide
    which applications are useful and which aren't.
</p>

<p> The Fraud Prevention Headers, though, appear to be very important to HMRC, to the extent
    that they have set up a separate Fraud Headers Team. Until the Fraud Prevention Headers are approved, the
    application can't go live.</p>

<p>When I submitted OpenVat for approval, the process was relatively smooth. There were a couple of queries over the
    Fraud Prevention Headers, I dealt with them, and the application was approved. The live application is now used by a
    few hundred people, and every month I
    receive an email from HMRC confirming that the fraud headers being sent by OpenVat are still correct.</p>

<p>So when I submitted the TaxUpdates application for approval, I was natually confident that the Fraud Prevention
    Headers would be
    fine. Both my applications have to send exactly the same headers, they are both set up the same way, and I therefore
    used exactly
    the same code that had already been approved. Unfortunately, 4 months later I'm still trying to get the headers
    approved. These are the issues that have arisen:</p>

<ul class="list">
    <li>Initially I was told that some of the headers were missing. This surprised me, as TaxUpdates is required to send
        exactly the same headers as OpenVat. Both applications are set up the same way, and use the same code to send
        the headers.</li>
    <li>Unfortunately, it's not possible to communicate directly with the Fraud Headers team. I email somebody within
        HMRC's Developer Support department, they then forward my email to the Fraud Headers team. Two or three weeks
        later, the Fraud Headers team replies to the Developer Support department, who then forward the response to me.
    </li>
    <li>After a couple of attempts to query how headers could be missing, I realised I wasn't going to get anywhere and
        each attempt at communicating was only causing a further two or three weeks' delay. Therefore, I spent a week
        stripping down my application to try to work out what was happening, and eventually realised that the problem
        was a bug at HMRC's end.</li>
    <li>When an application is being developed, it uses test data and HMRC provide various scenarios which can be
        tested. To switch between scenarios you send HMRC Test Scenario Headers - it turned out that some of the Test
        Scenario Headers were interfering with the Fraud Prevention Headers, this was what was causing HMRC to believe
        wrongly that
        Fraud Headers were missing.</li>
    <li>Using the convoluted communication method that HMRC provide, I tried several times to communicate this to the
        Fraud Headers team. I was told that the bug didn't exist, but eventually - after a couple of months - it seems
        that somebody at HMRC did quietly look into it, as the issue eventually disappeared. Great, now I should be
        able to get my headers approved.</li>
    <li>Unfortunately though, HMRC were still telling me that some of the required Fraud Headers were missing. When you
        develop an application through HMRC's Developer Hub, it's classified as a 'Sandbox' application. You can use the
        Sandbox to create and test your application, then once you're ready to apply for live credentials, the
        application is moved out of the Sandbox and classified under 'Request for production credentials'. Finally,
        after the application has been approved, it's classified as a 'Production application'. You can create several
        Sandbox applications, and I have done this - I've developed several versions of both my VAT and Income Tax
        application, and after I applied for production credentials for TaxUpdates I continued to develop another
        version of TaxUpdates in the Sandbox, as I want to add further functionality to it. This is a perfectly
        legitimate way of developing HMRC applications, HMRC themselves say, in their developer documentation, that once
        an application meets the minimum required functionality it can apply for production credentials, and further
        functionality can then be added as it's built.</li>
    <li>It turned out that HMRC's Fraud Headers department were looking at headers from Sandbox applications rather than
        the one that had applied for production credentials</li>
</ul>