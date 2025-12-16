<?php if (!$supporting_agent): ?>
<ol>
    <li>
        <p>Check your tax calculation</p>
        <p><a href="/individual-calculations/trigger-calculation">View Latest Tax Calculation</a></p>
    </li>
    <li>
        <p>Add or edit any items that will impact your tax position, if necessary.</p>

        <p>
            <a class="hmrc-connection" href="/business-details/list-all-businesses?year_end=true">Business Income</a>
            <span class="small">
                Claim Capital Allowances, make Accounting Adjustments and other adjustments to submitted Cumulative
                Summaries
            </span>
        </p>
        <p>
            <a href="/year-end/other-income">Other Income</a>
            <span class="small">
                Declare other income received in the tax year
            </span>
        </p>
        <p>
            <a href="/year-end/capital-gains">Capital Gains</a>
            <span class="small">
                Declare taxable disposals made in the tax year
            </span>
        </p>
        <p>
            <a href="/year-end/tax-reliefs">Tax Reliefs</a>
            <span class="small">
                Claim relief for expenses, investments, pension contributions and donations. Also includes CIS
                Deductions and Seafarers Deduction
            </span>
        </p>
        <p>
            <a href="/year-end/disclosures">Disclosures And Charges</a>
            <span class="small">
                Marriage Allowance, Voluntary Class 2 NIC, Tax Avoidance, Pension Charges, Child Benefit Charge
            </span>
        </p>
    </li>
    <li>
        <p>Make your Final Declaration for the year</p>
        <p><a class="hmrc-connection" href="/obligations/final-declaration">Final Declaration</a></p>
    </li>
</ol>

<?php else: ?>

<p>
    <a class="hmrc-connection" href="/business-details/list-all-businesses?year_end=true">Business Income</a>
    <span class="small">
        Claim Capital Allowances, make Accounting Adjustments and other adjustments to submitted Cumulative
        Summaries
    </span>
</p>

<?php endif; ?>