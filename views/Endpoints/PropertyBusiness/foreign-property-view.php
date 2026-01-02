<div class="regular-table">
    <table>
        <thead>
            <tr>
                <th>Property ID</th>
                <th>Property Name</th>
                <th>Country</th>
                <th>End Date</th>


            </tr>
        </thead>
        <tbody>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= $property['propertyId'] ?></td>
                    <td><?= $property['propertyName'] ?></td>
                    <td><?= getCountry($property['countryCode']) ?></td>
                    <td><?= $property['endDate'] ?? "None" ?></td>
                    <td>
                        <form action="/property-business/update-foreign-property" method="POST">

                            <input type="hidden" name="property_id" value="<?= $property['propertyId'] ?>">
                            <input type="hidden" name="property_name" value="<?= $property['propertyName'] ?>">

                            <button class="link">Update</button>

                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>



    </table>
</div>