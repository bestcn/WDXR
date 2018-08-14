USE guanjia16_new;


UPDATE `company_payment` SET `service_id` = (
  SELECT `id` FROM `company_service`
  WHERE
    `company_service`.`company_id` = `company_payment`.`company_id`
    AND
    `company_service`.`service_status` = 1
)
WHERE `company_payment`.`status` = 2 AND `type` = 4;